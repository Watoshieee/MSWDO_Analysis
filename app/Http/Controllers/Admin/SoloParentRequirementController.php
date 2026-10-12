<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SoloParentCategoryRequirement;
use App\Services\SoloParentCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SoloParentRequirementController extends Controller
{
    /**
     * Display master category requirements list.
     */
    public function index(Request $request)
    {
        // Automatically ensure default statutory requirements are seeded in DB if empty
        SoloParentCategoryService::seedDefaultMasterRequirementsIfNeeded();

        $rawCategories = SoloParentCategoryService::getCategories();
        $selectedCategory = $request->query('category', 'A1');
        if (!array_key_exists($selectedCategory, $rawCategories)) {
            $selectedCategory = 'A1';
        }

        // Format categories with title & description for the view
        $formattedCategories = [];
        foreach ($rawCategories as $code => $cat) {
            if (is_array($cat)) {
                $formattedCategories[$code] = [
                    'title'       => $cat['title'] ?? '',
                    'description' => $cat['description'] ?? '',
                ];
            } else {
                $formattedCategories[$code] = [
                    'title'       => (string)$cat,
                    'description' => "Statutory documentary requirements for Solo Parent Category {$code}.",
                ];
            }
        }

        $allRequirements = SoloParentCategoryRequirement::orderBy('order_num')
            ->orderBy('id')
            ->get();

        $requirementsByCategory = $allRequirements->groupBy('category_code');

        $activeRequirements = $requirementsByCategory->get($selectedCategory, collect());

        // Category counts
        $categoryCounts = [];
        foreach ($formattedCategories as $code => $cat) {
            $reqs = $requirementsByCategory->get($code, collect());
            $categoryCounts[$code] = [
                'total'  => $reqs->count(),
                'active' => $reqs->where('is_active', true)->count(),
            ];
        }

        $benefitRequirements = $requirementsByCategory->get(SoloParentCategoryService::BENEFIT_CODE_1_2, collect());

        return view('admin.solo-parent-requirements', [
            'categories'          => $formattedCategories,
            'selectedCategory'    => $selectedCategory,
            'currentCategory'     => $formattedCategories[$selectedCategory],
            'requirements'        => $activeRequirements,
            'categoryCounts'      => $categoryCounts,
            'allRequirements'     => $allRequirements,
            'benefitRequirements' => $benefitRequirements,
        ]);
    }

    /**
     * Store a newly created master requirement for a category.
     */
    public function store(Request $request)
    {
        $categories = array_keys(SoloParentCategoryService::getCategories());

        $validated = $request->validate([
            'category_code'    => 'required|in:' . implode(',', $categories),
            'requirement_name' => 'required|string|max:255',
            'group_key'        => 'nullable|string|max:60',
            'group_title'      => 'nullable|string|max:255',
            'is_or_group'      => 'nullable|boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'description'      => 'nullable|string|max:1000',
        ]);

        $groupKey = trim($validated['group_key'] ?? '');
        $groupTitle = trim($validated['group_title'] ?? '');
        $isOrGroup = (bool)($request->input('is_or_group', false));

        if (empty($groupKey)) {
            $groupKey = Str::slug($groupTitle ?: $validated['requirement_name'], '_');
        }
        if (empty($groupTitle)) {
            $groupTitle = $validated['requirement_name'];
        }

        $orderNum = $validated['sort_order'] ?? (SoloParentCategoryRequirement::where('category_code', $validated['category_code'])->max('order_num') + 10);

        SoloParentCategoryRequirement::create([
            'category_code'    => $validated['category_code'],
            'requirement_name' => trim($validated['requirement_name']),
            'group_key'        => $groupKey,
            'group_title'      => $groupTitle,
            'is_or_group'      => $isOrGroup,
            'is_active'        => true,
            'order_num'        => $orderNum,
            'description'      => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.solo-parent-requirements.index', ['category' => $validated['category_code']])
            ->with('success', "New requirement \"{$validated['requirement_name']}\" added to Category {$validated['category_code']}.");
    }

    /**
     * Update an existing master requirement.
     */
    public function update(Request $request, $id)
    {
        $requirement = SoloParentCategoryRequirement::findOrFail($id);
        $categories = array_keys(SoloParentCategoryService::getCategories());

        $validated = $request->validate([
            'category_code'    => 'required|in:' . implode(',', $categories),
            'requirement_name' => 'required|string|max:255',
            'group_key'        => 'nullable|string|max:60',
            'group_title'      => 'nullable|string|max:255',
            'is_or_group'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'description'      => 'nullable|string|max:1000',
        ]);

        $groupKey = trim($validated['group_key'] ?? '');
        $groupTitle = trim($validated['group_title'] ?? '');

        if (empty($groupKey)) {
            $groupKey = Str::slug($groupTitle ?: $validated['requirement_name'], '_');
        }
        if (empty($groupTitle)) {
            $groupTitle = $validated['requirement_name'];
        }

        $requirement->update([
            'category_code'    => $validated['category_code'],
            'requirement_name' => trim($validated['requirement_name']),
            'group_key'        => $groupKey,
            'group_title'      => $groupTitle,
            'is_or_group'      => (bool)$request->input('is_or_group', false),
            'is_active'        => (bool)$request->input('is_active', true),
            'order_num'        => $validated['sort_order'] ?? $requirement->order_num,
            'description'      => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.solo-parent-requirements.index', ['category' => $requirement->category_code])
            ->with('success', "Requirement \"{$requirement->requirement_name}\" updated successfully.");
    }

    /**
     * Toggle active status of a master requirement.
     */
    public function toggleActive($id)
    {
        $requirement = SoloParentCategoryRequirement::findOrFail($id);
        $newState = !$requirement->is_active;
        $requirement->update(['is_active' => $newState]);

        $statusLabel = $newState ? 'activated' : 'deactivated';

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $newState,
                'message'   => "Requirement \"{$requirement->requirement_name}\" has been {$statusLabel}.",
            ]);
        }

        return redirect()->back()
            ->with('success', "Requirement \"{$requirement->requirement_name}\" has been {$statusLabel}.");
    }

    /**
     * Reset master requirements for a category (or all) to the system defaults.
     */
    public function resetDefaults(Request $request)
    {
        $categoryCode = $request->input('category_code');
        $defaults = SoloParentCategoryService::getDefaultDefinitions();

        if ($categoryCode && array_key_exists($categoryCode, $defaults)) {
            SoloParentCategoryRequirement::where('category_code', $categoryCode)->delete();
            foreach ($defaults[$categoryCode] as $item) {
                SoloParentCategoryRequirement::create($item);
            }
            $msg = "Default requirements for Category {$categoryCode} have been restored.";
        } else {
            SoloParentCategoryRequirement::truncate();
            foreach ($defaults as $code => $items) {
                foreach ($items as $item) {
                    SoloParentCategoryRequirement::create($item);
                }
            }
            $msg = "All statutory default requirements have been restored for all categories.";
        }

        return redirect()->route('admin.solo-parent-requirements.index', ['category' => $categoryCode ?: 'A1'])
            ->with('success', $msg);
    }
}