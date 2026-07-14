@php
    $aicsStorageKey = 'aicsMedical';
    $aicsProgramType = 'AICS_Medical';
    $aicsProgramLabel = 'AICS Medical Assistance';
    $aicsPageTitle = 'AICS Medical Assistance - MSWDO';
    $aicsTitleEn = 'Medical Assistance';
    $aicsTitleTl = 'Tulong Medikal';
    $aicsBatchRoute = route('user.aics-medical-upload-batch');
    $aicsSingleRoute = route('user.aics-medical-upload');
    $aicsBackRoute = route('user.aics-category');
    $aicsGuideSteps = [
        ['en'=>'Schedule an Appointment','tl'=>'Mag-schedule ng Appointment','descEn'=>'Book a face-to-face or online interview with the MSWDO for medical assistance.','descTl'=>'Mag-book ng harapan o online na panayam sa MSWDO para sa tulong medikal.'],
        ['en'=>'Wait for Confirmation','tl'=>'Hintayin ang Kumpirmasyon','descEn'=>'The admin will review and confirm your appointment via website and email.','descTl'=>'Susuriin at kukumpirmahin ng admin ang iyong appointment sa website at email.'],
        ['en'=>'Attend the Interview','tl'=>'Dumalo sa Panayam','descEn'=>'Go to the MSWDO office or attend your scheduled online interview.','descTl'=>'Pumunta sa opisina ng MSWDO o dumalo sa online na panayam.'],
        ['en'=>'Eligibility Assessment','tl'=>'Eligibility Assessment','descEn'=>'MSWDO will assess your eligibility for medical financial assistance.','descTl'=>'Susuriin ng MSWDO ang iyong eligibility para sa tulong medikal.'],
        ['en'=>'Submit Requirements','tl'=>'Isumite ang mga Requirements','descEn'=>'If eligible, upload all required documents for admin review.','descTl'=>'Kung karapat-dapat, i-upload ang lahat ng kinakailangang dokumento.'],
        ['en'=>'Receive Assistance','tl'=>'Tumanggap ng Tulong','descEn'=>'Once approved, MSWDO will process your medical assistance.','descTl'=>'Kapag naaprubahan, ipoproseso ng MSWDO ang iyong tulong medikal.'],
    ];
@endphp
@include('user.partials.aics-application')
