<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE mouvements_caisse MODIFY COLUMN type ENUM('encaissement','ouverture','fermeture','retrait','depot','depense') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE mouvements_caisse MODIFY COLUMN type ENUM('encaissement','ouverture','fermeture','retrait','depot') NOT NULL");
    }
};
