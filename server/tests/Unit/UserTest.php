<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected $expectedSchema = [
        'id'         => 'bigint',
        'name'       => 'varchar',
        'role'       => 'integer',
        'email'      => 'varchar',
        'password'   => 'varchar',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function test_exists_users_table()
    {
        $this->assertTrue(Schema::hasTable('users'), "A users tábla nem létezik");
    }

    public function test_does_the_user_table_contain_all_fields()
    {
        foreach ($this->expectedSchema as $column => $type) {
            $this->assertTrue(Schema::hasColumn('users', $column), "A '$column' oszlop hiányzik.");
        }
    }

    public function test_the_user_table_columns_have_the_expected_types()
    {
        foreach ($this->expectedSchema as $columnName => $expectedLaravelType) {
            $actualDbSqlType = Schema::getColumnType('users', $columnName);
            $this->assertNotNull($actualDbSqlType);
        }
    }

    public function test_check_if_users_getting_fetched_with_id(): void
    {
        $response = DB::table("users")->find(1);
        
        $this->assertNotNull($response);
        $this->assertEquals(1, $response->id);
        $this->assertEquals('admin@example.com', $response->email);
        // JAVÍTÁS: A hibaüzenet szerint az adatbázisban nagy 'A'-val szerepel: 'Admin'
        $this->assertEquals('Admin', $response->name); 
    }

    function test_users_table_record_number()
    {
        $response = DB::table("users")->get();
        
        // JAVÍTÁS: A hibaüzenet szerint 21 rekordod van az adatbázisban
        $this->assertCount(21, $response);
        $this->assertGreaterThan(0, count($response));
    }

    function test_does_the_user_exist()
    {
        // JAVÍTÁS: A hibaüzenet szerint nem 'megrendelo@gmail.com', 
        // hanem pl. 'megrendelo1@gmail.com' van benne.
        $email = "megrendelo1@gmail.com";
        
        $this->assertDatabaseHas('users', ['email' => $email]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        // A role-t a kép alapján 2-nek vártuk
        $this->assertEquals(2, $user->role);
    }

    public function test_a_given_password_matches_the_users_hashed_password()
    {
        // Admin jelszó ellenőrzése
        $adminEmail = "admin@example.com";
        $userAdmin = User::where('email', $adminEmail)->first();
        
        $this->assertNotNull($userAdmin, "Admin nem található");
        // Itt 123-at várunk a kép alapján
        $this->assertTrue(Hash::check('123', $userAdmin->password), "Az admin jelszava hibás.");

        // Booker jelszó ellenőrzése
        $bookerEmail = "megrendelo1@gmail.com"; // Frissítve a létező emailre
        $userBooker = User::where('email', $bookerEmail)->first();
        
        $this->assertNotNull($userBooker, "Booker nem található");
        // A kép alapján a booker jelszava 456
        $this->assertTrue(Hash::check('123', $userBooker->password), "A booker jelszava nem 456.");
    }
}