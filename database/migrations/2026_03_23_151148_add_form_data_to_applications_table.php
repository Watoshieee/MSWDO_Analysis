public function up()
{
    Schema::table('applications', function (Blueprint $table) {
        $table->json('form_data')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('applications', function (Blueprint $table) {
        $table->dropColumn('form_data');
    });
}