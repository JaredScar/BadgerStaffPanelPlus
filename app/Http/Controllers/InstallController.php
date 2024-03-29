<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Sessions;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

use mysqli;

class InstallController extends Controller {

    // Display the content for each installation step

    // Move to the next page
    public function moveToNextPage(Request $request) {
        $currentPage = $request->input('currentPage');
        \Log::info('Current Page: ' . $currentPage);
        // Determine the next page
        $nextPage = $this->determineNextPage($currentPage);
        \Log::info('Redirecting from currentpage ' . $currentPage . ' to the new page: ' . $nextPage);
        // Redirect to the next page or stay on the current page with errors
        if ($nextPage !== null) {
            return redirect()->route('install', ['page' => $nextPage]);

        } else {
            return redirect()->route('DASHBOARD'); 
        }
    }

    // Determine the next page
    private function determineNextPage($currentPage) {
        // Implement your logic to determine the next page
        switch ($currentPage) {
            case 'welcome':
                return 'agreement';
            case 'agreement':
                return 'config';
            case 'config':
                return 'database';
            case 'database':
                return 'adminuser';
            case 'adminuser':
                return 'discord';
            case 'discord':
                return 'groups';
            case 'groups':
                return 'completed';
            case 'completed':
                return null; // No next page after completed
            default:
                return null;
        }
    }

    // Specific functions to execute on each page
    private function executeWelcomeFunctions() {
    }
    public function showPage($page = 'welcome') {
        //$mysqlV = mysql_get_server_info(); removed support for this might revisit another time.
        $laravelV = app()->version();
        $phpV = phpversion();
        $data = [];
        if (extension_loaded('ctype')) { $data["ext_ctype"] = true; } else { $data["ext_ctype"] = false; }
        if (extension_loaded('openssl')) { $data["ext_openssl"] = true; } else { $data["ext_openssl"] = false; }
        if (extension_loaded('curl')) { $data["ext_curl"] = true; } else { $data["ext_curl"] = false; }
        if (extension_loaded('pcre')) { $data["ext_pcre"] = true; } else { $data["ext_pcre"] = false; }
        if (extension_loaded('dom')) { $data["ext_dom"] = true; } else { $data["ext_dom"] = false; }
        if (extension_loaded('pdo')) { $data["ext_pdo"] = true; } else { $data["ext_pdo"] = false; }
        if (extension_loaded('fileinfo')) { $data["ext_fileinfo"] = true; } else { $data["ext_fileinfo"] = false; }
        if (extension_loaded('session')) { $data["ext_session"] = true; } else { $data["ext_session"] = false; }
        if (extension_loaded('filter')) { $data["ext_filter"] = true; } else { $data["ext_filter"] = false; }
        if (extension_loaded('tokenizer')) { $data["ext_tokenizer"] = true; } else { $data["ext_tokenizer"] = false; }
        if (extension_loaded('hash')) { $data["ext_hash"] = true; } else { $data["ext_hash"] = false; }
        if (extension_loaded('xml')) { $data["ext_xml"] = true; } else { $data["ext_xml"] = false; }
        if (extension_loaded('mbstring')) { $data["ext_mbstring"] = true; } else { $data["ext_mbstring"] = false; }
        $data['css_path'] = 'installer';
        $data['view_name'] = 'install';
        $data['customize'] = false;
        $TOS = public_path('/tos/tos.txt');
        $thatenv = public_path('install/init/example.env');
        //$tosContent = "";
        if (file_exists($TOS)) {
            $tosContent = File::get($TOS);
        }
        if (file_exists($thatenv)) {
            $envContent = File::get($thatenv);
        } else {
            $envContentArray = [];
        }
        switch ($page) {
            case 'welcome':
                $this->executeWelcomeFunctions();
                return view('install.welcome', compact('laravelV', 'phpV', 'data'));
            case 'agreement':
                return view('install.agreement', compact('data', 'tosContent'));
            case 'config':
                $this->executeConfigFunctions();
                return view('install.config', compact('data', 'envContent', 'thatenv'));
            case 'database':
                $this->executeDatabaseFunctions();
                return view('install.database', compact('data'));
            case 'adminuser':
                $this->executeAdminuserFunctions();
                return view('install.adminuser', compact('data'));
            case 'discord':
                $this->executeDiscordFunctions();
                return view('install.completed', compact('data'));
            case 'groups':
                $this->executeGroupFunctions();
                return view('install.completed', compact('data'));
            case 'Complete':
                $this->executeCompleteFunctions();
                return view('install.completed', compact('data'));
            default:
                $this->executeWelcomeFunctions();
                return view('install.welcome', compact('laravelV', 'phpV', 'data')); 
        }
    }

    private function executeConfigFunctions() {

    }

    private function executeDatabaseFunctions() {
    }

    private function executeAdminuserFunctions() {
    }

    private function executeDiscordFunctions() {
    }

    private function executeGroupsFunctions() {
    }

    private function executeCompleteFunctions() {
    }
    public function DBInstaller($function) {
        function mysqlExecutioner($queryy, $type) {
            $dbHost = getenv('DB_HOST');
            $dbUsername = getenv('DB_USERNAME');
            $dbPassword = getenv('DB_PASSWORD');
            $dbName = getenv('DB_DATABASE');
            $dbPort = getenv('DB_PORT');
            if ($type == 0) {
                try {
                    // atlast we test the DB connection cuz everything else is set.
                    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, '', $dbPort);
                    if ($conn->connect_error) {
                        throw new Exception("Connection failed: " . $conn->connect_error);
                    } else {
                        $result = $conn->query($queryy);
                        
                        if ($result == false) {
                            throw new Exception("Error executing MySQL query: " . $conn->error);
                        }
                    
                        // Fetching result
                        if ($result == true) {
                            if ($result->num_rows === 0) {
                                $collectiveResult = "success";
                            } else {
                                $collectiveResult = array();
                                while ($row = $result->fetch_assoc()) {
                                    $collectiveResult[] = $row;
                                }
                            }
                        }
                        // Closing connection
                        $conn->close();
                    
                        return $collectiveResult;
                    
                    }
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            } else if ($type == 1) {
                try {
                    // atlast we test the DB connection cuz everything else is set.
                    $dbName = getenv('DB_DATABASE');
                    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName, $dbPort);
                    if ($conn->connect_error) {
                        throw new Exception("Connection failed: " . $conn->connect_error);
                    } else {
                        $result = $conn->query($queryy);
                        if ($result == false) {
                            throw new Exception("Error executing MySQL query: " . $conn->error);
                        } else {
                            return $result;
                        }
                        // Closing connection
                        $conn->close();
                    
                    }
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            }
        }
        if ($function == 'login') {
            $query = 'create database ' . getenv('DB_DATABASE');
            $result = mysqlExecutioner($query, 0);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '5',
                    'function' => 'createDB',
                    'tasktitle' => 'Logging Into Database',
                    'taskdescription' => 'Just Logging you in for this database operation',
                    'taskicon' => 'check-circle',
                    'outputfunction' => 'Login Attempt',
                    'outputaction' => 'Attempting to log into the database server with the login provided in the ENV file.',
                    'prodesc' => 'Logging into the database server..',
                    'result' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-staff') {
            $query = 
            'CREATE TABLE IF NOT EXISTS staff (
                staff_id INT(128) AUTO_INCREMENT PRIMARY KEY,
                staff_username VARCHAR(128) NOT NULL,
                staff_password VARCHAR(255) NOT NULL,
                staff_email VARCHAR(255),
                staff_discord BIGINT(128),
                server_id INT(128) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '10',
                    'sqlfunction' => 'table-staff', // current SQL function
                    'description' => 'Successfully completed the creatation of the Database Table "staff".',
                    'tasktitle' => 'Created Staff',
                    'taskdescription' => 'Successfully Created Database Table "staff"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-staff-two', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-staff-two') {
            $query = 
            'CREATE UNIQUE INDEX staff_username ON staff("staff_username"); CREATE UNIQUE INDEX staff_email ON staff("staff_email")'
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '15',
                    'sqlfunction' => 'table-staff-two', // current SQL function
                    'description' => 'Successfully Altered Table Users adding an index to the field "staff".',
                    'tasktitle' => 'Altered Staff',
                    'taskdescription' => 'Successfully Created index on table "staff"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-users', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-users') {
            $query = 
            'CREATE TABLE IF NOT EXISTS users (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '20',
                    'sqlfunction' => 'table-users', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "users".',
                    'tasktitle' => 'Created users',
                    'taskdescription' => 'Successfully Created Database Table "users"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-users-two', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-users-two') {
            $query = 
            'CREATE UNIQUE INDEX users_email_unique ON users("email")';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '23',
                    'sqlfunction' => 'table-users-two', // current SQL function
                    'description' => 'Successfully Altered Table Users adding an index to the field "email".',
                    'tasktitle' => 'Created users',
                    'taskdescription' => 'Successfully Created index on table "users"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-warns', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-warns') {
            $query = 
            'CREATE TABLE IF NOT EXISTS warns (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                player_id INT(128) NOT NULL,
                reason VARCHAR(255) NOT NULL,
                staff_id INT(128) NOT NULL,
                server_id INT(128) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '30',
                    'sqlfunction' => 'table-warns', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "warns".',
                    'tasktitle' => 'Created Warns',
                    'taskdescription' => 'Successfully Created Database Table "warns"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-bans', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-bans') {
            $query = 
            'CREATE TABLE IF NOT EXISTS bans (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                player_id INT(128) NOT NULL,
                reason VARCHAR(255) NOT NULL,
                staff_id INT(128) NOT NULL,
                expires INT(1) NULL,
                expired_date DATETIME NULL,
                server_id INT(128) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '35',
                    'sqlfunction' => 'table-bans', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "bans".',
                    'tasktitle' => 'Created Bans',
                    'taskdescription' => 'Successfully Created Database Table "bans"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-commends', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-commends') {
            $query = 
            'CREATE TABLE IF NOT EXISTS commends (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                player_id INT(128) NOT NULL,
                reason VARCHAR(255) NOT NULL,
                staff_id INT(128) NOT NULL,
                server_id INT(128) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '40',
                    'sqlfunction' => 'table-commends', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "commends".',
                    'tasktitle' => 'Created Commends',
                    'taskdescription' => 'Successfully Created Database Table "commends"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-failed-jobs', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-failed-jobs') {
            $query = 
            'CREATE TABLE IF NOT EXISTS failed_jobs (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                uuid INT(255) NOT NULL,
                connection TEXT NOT NULL,
                queue TEXT NOT NULL,
                payload LONGTEXT NOT NULL,
                exception LONGTEXT NOT NULL,
                failed_at TIMESTAMP NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '45',
                    'sqlfunction' => 'table-failed-jobs', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "failed_jobs".',
                    'tasktitle' => 'Created users',
                    'taskdescription' => 'Successfully Created Database Table "failed_jobs"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-failed-jobs-two', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-failed-jobs-two') {
            $query = 
            'CREATE UNIQUE INDEX failed_jobs_uuid_unique ON failed_jobs("uuid")';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '47',
                    'sqlfunction' => 'table-failed-jobs-two', // current SQL function
                    'description' => 'Successfully altered table "failed_jobs" adding an index for "uuid".',
                    'tasktitle' => 'Altered failed_jobs',
                    'taskdescription' => 'Successfully Created index on table "failed_jobs"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-kicks', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-kicks') {
            $query = 
            'CREATE TABLE IF NOT EXISTS kicks (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                player_id INT(128) NOT NULL,
                reason VARCHAR(255) NOT NULL,
                staff_id INT(128) NOT NULL,
                server_id INT(128) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '50',
                    'sqlfunction' => 'table-kicks', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "kicks".',
                    'tasktitle' => 'Created Kicks',
                    'taskdescription' => 'Successfully Created Database Table "kicks"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-layouts', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-layouts') {
            $query = 
            'CREATE TABLE IF NOT EXISTS layouts (
                staff_id INT(128) AUTO_INCREMENT NULL,
                view VARCHAR(128) NULL,
                widget_type VARCHAR(128) NULL,
                col INT(128) NULL,
                row INT(128) NULL,
                size_x DATETIME NULL,
                size_y DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '65',
                    'sqlfunction' => 'table-layouts', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "layouts".',
                    'tasktitle' => 'Created Layouts',
                    'taskdescription' => 'Successfully Created Database Table "layouts"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-migrations', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-migrations') {
            $query = 
            'CREATE TABLE IF NOT EXISTS migrations (
                id INT(10) AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT(11) NOT NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '70',
                    'sqlfunction' => 'table-migrations', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "migrations".',
                    'tasktitle' => 'Created Commends',
                    'taskdescription' => 'Successfully Created Database Table "migrations"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-notes', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-notes') {
            $query = 
            'CREATE TABLE IF NOT EXISTS notes (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                player_id INT(128) NOT NULL,
                note VARCHAR(255) NOT NULL,
                staff_id INT(128) NOT NULL,
                server_id INT(128) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '75',
                    'sqlfunction' => 'table-notes', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "notes".',
                    'tasktitle' => 'Created Notes',
                    'taskdescription' => 'Successfully Created Database Table "notes"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-password-resets', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-password-resets') {
            $query = 
            'CREATE TABLE IF NOT EXISTS password_resets (
                id VARCHAR(255) AUTO_INCREMENT PRIMARY KEY,
                token INT(255) NOT NULL,
                created_at TIMESTAMP NULL,
                expires_at TIMESTAMP NULL,
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '80',
                    'sqlfunction' => 'table-password-resets', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "password_resets".',
                    'tasktitle' => 'Created Password_resets',
                    'taskdescription' => 'Successfully Created Database Table "password_resets"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-personal-access-tokens', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-personal-access-tokens') {
            $query = 
            'CREATE TABLE IF NOT EXISTS personal_access_tokens (
                id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
                tokenable_type VARCHAR(255) NOT NULL,
                tokenable_id BIGINT(20) NOT NULL,
                name VARCHAR(255) NOT NULL
                token VARCHAR(64) NOT NULL,
                abilities TEXT NULL,
                last_used_at TIMESTAMP NULL,
                expires_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '85',
                    'sqlfunction' => 'table-personal-access-tokens', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "personal_access_tokens".',
                    'tasktitle' => 'Created Personal_access_tokens',
                    'taskdescription' => 'Successfully Created Database Table "personal_access_tokens"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-personal-access-tokens-two', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-personal-access-tokens-two') {
            $query = 
            'CREATE UNIQUE INDEX personal_access_tokens_token_unique ON personal_access_tokens("token"); CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON personal_access_tokens("tokenable_type", "tokenable_id")';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '87',
                    'sqlfunction' => 'table-personal-access-tokens-two', // current SQL function
                    'description' => 'Successfully altered table "personal_access_tokens" adding an index for Multiple Options".',
                    'tasktitle' => 'Altered failed_jobs',
                    'taskdescription' => 'Successfully Created index on table "failed_jobs"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-players', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-players') {
            $query = 
            'CREATE TABLE IF NOT EXISTS players (
                server_id INT(128) NULL,
                player_id INT(128) AUTO_INCREMENT PRIMARY KEY,
                discord_id BIGINT(128) NULL,
                game_license VARCHAR(128) NULL,
                steam_id VARCHAR(32) NULL,
                live VARCHAR(128) NULL,
                xbl VARCHAR(128) NULL,
                ip VARCHAR(128) NULL,
                last_player_name VARCHAR(255) NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '90',
                    'sqlfunction' => 'players', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "players".',
                    'tasktitle' => 'Created Players',
                    'taskdescription' => 'Successfully Created Database Table "players"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-players-two', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-players-two') {
            $query = 
            'CREATE UNIQUE INDEX server_id ON players("server_id"); CREATE UNIQUE INDEX game_license ON players("game_license")';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '92',
                    'sqlfunction' => 'table-personal-access-tokens-two', // current SQL function
                    'description' => 'Successfully altered table "players" adding an index for "server_id" and "game_license".',
                    'tasktitle' => 'Altered Players',
                    'taskdescription' => 'Successfully Created index on table "players"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-player-data', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-player-data') {
            $query = 
            'CREATE TABLE IF NOT EXISTS notes (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                server_id INT(128) NULL,
                player_id INT(128) NULL,
                playtime INT(255) NULL,
                trust_score INT(255) NULL,
                joins INT(255) NULL,
                last_join_date DATETIME NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '95',
                    'sqlfunction' => 'table-player-data', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "player-data".',
                    'tasktitle' => 'Created Player-data',
                    'taskdescription' => 'Successfully Created Database Table "player-data"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-player-data-two', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-player-data-two') {
            $query = 
            'CREATE UNIQUE INDEX server_id ON player_data("server_id"); CREATE UNIQUE INDEX player_id ON player_data("player_id")';
            $result = mysqlExecutioner($query, 1);
            if ($reset) {
                $response = [
                    'status' => 'success',
                    'percent' => '98',
                    'sqlfunction' => 'table-player-data-two', // current SQL function
                    'description' => 'Successfully altered table "player-data" adding an index for "server_id" and "player_id".',
                    'tasktitle' => 'Altered Players',
                    'taskdescription' => 'Successfully Created index on table "players"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'table-player-data', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $responsee;
            } else {
                return response()-json(['error' => $result]);
            }
        }
        if ($function == 'table-servers') {
            $query = 
            'CREATE TABLE IF NOT EXISTS notes (
                id INT(128) AUTO_INCREMENT PRIMARY KEY,
                server_name VARCHAR(255) NULL,
                server_slug VARCHAR(255) NULL
            )';
            $result = mysqlExecutioner($query, 1);
            if ($result) {
                $response = [
                    'status' => 'success',
                    'percent' => '100',
                    'sqlfunction' => 'table-servers', // current SQL function
                    'description' => 'Successfully completed the creation of the Database Table "servers".',
                    'tasktitle' => 'Created Servers',
                    'taskdescription' => 'Successfully Created Database Table "servers"',
                    'taskicon' => 'check-circle',
                    'newfunction' => 'completed', // only used to direct new function execution.
                    'sqlresult' => $result,
                ];
                return $response;
            } else {
                return response()-json(['error' => $result]);
            }
        }
    }
        
    }
    public function createDB(Request $request) {
        $function = $request->input('function_to_execute');
        $result = $this->DBInstaller($function);
        return response()->json($result);
    }
    public function initCreation() {
        sleep(2);
        return [
            'status' => 'error',
            'percent' => '25',
            'function' => 'initCreation',
            'tasktitle' => 'Logging Into Database',
            'taskdescription' => 'Just Logging you in for this database operation',
            'taskicon' => 'check-circle',
            'outputfunction' => 'Login Attempt',
            'outputaction' => 'Attempting to log into the database server with the login provided in the ENV file.',
            'prodesc' => 'Logging into the database server..',
        ];
    }
    public function databaseCreationIfNull() {
        sleep(2);
    }
    public function initTables() {
        sleep(2);
    }
    public function createTables() {
        sleep(2);
    }
    public function insertData() {
        sleep(2);
    }
    public function verifyComplete() {
        sleep(2);
    }
    public function createEnvFunction(Request $request) {
        function concatErrors(array $arrays): array {
            $combinedArray = [];
        
            foreach ($arrays as $index => $array) {
                foreach ($array as $key => $value) {
                    $combinedArray[$key] = $value;
                }
            }
            if (count($combinedArray) == 0) {
                $combinedArray = ['fields' => 'success', 'db_test' => 'success'];
                return $combinedArray;
            } else {
                return $combinedArray;    
            }
            
        }
        
        function databaseTestFunction($db_connection, $db_host, $db_username, $db_password, $db_database, $db_port) {
            if (isset($db_connection)) {
                $database_schema = [];
        
                if ($db_connection == 'mysql' || $db_connection == 'mariadb') {
                    if (isset($db_host) && isset($db_port)) {
                        if (isset($db_username) && isset($db_password)) {
                            try {
                                // atlast we test the DB connection cuz everything else is set.
                                $conn = new mysqli($db_host, $db_username, $db_password, "", $db_port);
                                if ($conn->connect_error) {
                                    return 'error_connecting';
                                } else {
                                    $conn->close();
                                    return 'connection_successful';
                                }
                            } catch (Exception $e) {
                                return 'test_failed' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                            }
                        } else {
                            return 'database_user_password_null';
                        }
                    } else {
                        return 'database_host_port_null';
                    }
                } else {
                    return 'unsupported_database_server';
                }
            }
        }
        function validateThenGenerate($app_name, $app_env, $app_key, $app_debug, $app_url, $master_api_key, $use_captcha, $captcha_vendor, $google_captcha_key, $google_captcha_secret, $use_discord, $discord_use_case, $discord_redirect_uri, $discord_redirect_auth, $master_admin_discord_id, $master_admin_role_id, $log_channel, $log_deprecation_channel, $log_level, $db_connection, $db_host, $db_username, $db_password, $db_port, $db_database, $broadcast_driver, $cache_driver, $filesystem_disk, $queue_connection, $session_driver, $session_lifetime, $memcached_host, $mail_mailer, $mail_host, $mail_username, $mail_port, $mail_password, $mail_from_address, $mail_from_name, $bcrypt_rounds) 
        {
            $errors = [];
            $db_return = [];
            //app_name
            if(!preg_match('/^[a-zA-Z0-9_\.+]+$/', $app_name)) {
                $errors[] = array('app_name' => array('status' => 'error', 'message' => 'Invalid Application Name. Please revise your selection to include characters A-Z, 0-9', 'errorid' => '1'));
            }
        
            //app_env
            if (strlen($app_env) < 2 || strlen($app_env) == 0) {
                $errors[] = array('app_env' => array('status' => 'error', 'message' => 'Invalid Application Enviornment. Please revise your selection to include more than 2 characters.', 'errorid' => '2'));
            }
        
            //app_debug
            if ($app_debug !== 'true' && $app_debug !== 'false') {
                $errors[] = array('app_debug' => array('status' => 'error', 'message' => 'Invalid Debug value, this must be true or false. Please revise your selection to being either enabled or disabled (true or false).'));
            }
        
            //app_key
            if (strlen($app_key) < 1) {
                $errors[] = array('app_key' => array('status' => 'error', 'message' => 'Invalid Application Key. It is highly recommended this to be left its default value.'));
            }
        
            //app_url
            if (strlen($app_url) < 1) {
                $errors[] = array('app_url' => array('status' => 'error', 'message' => 'Invalid Application URL. Your URL cannot be 0 characters. Please revise your selection.', 'current_value' => $app_url));
            } else if (str_contains($app_url, ".") == false) {
                $errors[] = array('app_url' => array('status' => 'error', 'message' => 'Invalid Application URL. You are missing a . in your URL. FQDNs require URLs to have a name and a suffix such as .com .net .org ect.'));
            } else if (str_contains($app_url, "http") == false) {
                $errors[] = array('app_url' => array('status' => 'error', 'message' => 'Invalid Application URL. You are missing the protocol in your URL. For example if an insecure server your URL might have an http:// or for a secure url they might include a https://', 'current_value' => $app_url));
            }
        
            //master_api_key
            if (strlen($master_api_key) < 1) {
                $errors[] = array('master_api_key' => array('status' => 'error', 'message' => 'Invalid Master API Key. You must have one generated either from using the default installation ENV of using another method. This cannot be blank.'));
            }
        
            //use_captcha
            if ($use_captcha !== 'true' && $use_captcha !== 'false') {
                $errors[] = array('use_captcha' => array('status' => 'error', 'message' => 'Invalid Use Captcha Value. You must either choose to not use a captcha or to use a captcha.'));
            }
        
            //captcha_vendor
            if ($captcha_vendor !== 'google') {
                $errors[] = array('captcha_vendor' => array('status' => 'error', 'message' => 'Invalid Captcha Vendor. At this time this application will only support Google Recaptcha.'));
            }
        
            //google_captcha_key & google_captcha_secret
            if ($captcha_vendor == "google" && $use_captcha == 'true') {
                //google_captcha_key
                if (strlen($google_captcha_key) < 1) {
                    $errors[] = array('google_captcha_key' => array('status' => 'error', 'message' => 'Invalid Google Captcha Key. If you need assistance in generating a google captcha key please <a href="#">click here</a>.'));
                }
                //google_captcha_secret
                if (strlen($google_captcha_secret) < 1) {
                    $errors[] = array('google_captcha_secret' => array('status' => 'error', 'message' => 'Invalid Google Captcha Secret. If you need assistance in generating a google captcha secret please <a href="#">click here</a>.'));
                }
            }
        
            //use_discord 
            if ($use_discord !== 'true' && $use_discord !== 'false') {
                $errors[] = array('use_discord' => array('status' => 'error', 'message' => 'Invalid Use Discord. You can either choose to enable or not enable it. You cannot specify otherwise.'));
            }
        
            //discord_use_case
            if ($use_discord == 'true' && $discord_use_case !=='full' && $discord_use_case !=='logging' && $discord_use_case !=='auth_only') {
                $errors[] = array('discord_use_case' => array('status' => 'error', 'message' => 'Invalid Discord Use Case. To learn more about the options and what to choose, please refer to our <a href="#">documentation</a>.'));
            }
        
            //discord_redirect_uri
            if ($use_discord == 'true') {
                if ($discord_use_case == 'full' || $discord_use_case == 'logging' || $discord_use_case == "auth_only") {
                    if (strlen($discord_redirect_uri) < 1) {
                        $errors[] = array('discord_redirect_uri' => array('status' => 'error', 'message' => 'Invalid Discord Redirect URI. To learn more about the options and what to choose, please refer to our <a href="#">documentation</a>.'));
                    }
                }
            }
        
            //discord_redirect_auth
            if ($use_discord == 'true') {
                if ($discord_use_case == 'full' || $discord_use_case == 'logging' || $discord_use_case == "auth_only") {
                    if (strlen($discord_redirect_auth) < 1) {
                        $errors[] = array('discord_redirect_auth' => array('status' => 'error', 'message' => 'Invalid Discord Redirect Auth. To learn more about the options and what to choose, please refer to our <a href="#">documentation</a>.'));
                    }
                }
            }
        
            //master_admin_discord_id
            if ($use_discord == 'true') {
                if (strlen($discord_use_case) > 0) {

                    if (strlen($master_admin_discord_id) == 18) {
                    } else {
                        $errors[] = array('master_admin_discord_id' => array('status' => 'error', 'message' => 'The Master Admin Discord ID should be set to a value not less than or exceeding 13 numerical digits. There is no need to include any brackets, @ symbols or signs.'));
                    }
                    if (!is_numeric($master_admin_discord_id)) {
                        $errors[] = array('master_admin_discord_id_2' => array('status' => 'error', 'message' => 'The Master Admin Discord ID should be entirely numeric in value, letters and specicial characters are forbidden.'));
                    }
                }
            }
        
            //master_admin_role_id
            if ($use_discord == 'true') {
                if (strlen($discord_use_case) > 0) {
                    if (strlen($master_admin_role_id) == 18) {
                    } else {
                        $errors[] = array('master_admin_role_id' => array('status' => 'error', 'message' => 'The Master Admin Role ID should be set to a value not less than or exceeding 13 numerical digits. There is no need to include any brackets, & symbols or signs.'));
                    }
                    if (!is_numeric($master_admin_role_id)) {
                        $errors[] = array('master_admin_role_id_2' => array('status' => 'error', 'message' => 'The Master Admin Role ID should be entirely numeric in value, letters and specicial characters are forbidden.'));
                    }
                }
            }
        
            //log_channel
            if (strlen($log_channel) < 1) {
                $errors[] = array('log_channel' => array('status' => 'error', 'message' => 'The Field Log Channel has a value less than 1. Please set this value before continuing.'));
            }
        
            //log_deprecation_channel
            if (strlen($log_deprecation_channel) < 1) {
                $errors[] = array('log_deprecation_channel' => array('status' => 'error', 'message' => 'The Field Log Deprecations Channel has a value of less than 1. Please set this value before continuing.'));
            }
            
            //log_level
            if (strlen($log_level) < 1) {
                $errors[] = array('log_level' => array('status' => 'error', 'message' => 'The Field Log Level has a value of less than 1. Please set this value before continuing.'));
            }
        
            //db_connection
            if (strlen($db_connection) < 1) {
                $errors[] = array('db_connection' => array('status' => 'error', 'message' => 'You must specify the Database Driver for this installation. A Database is required to store information required for this panels functionality.'));
            } 
        
            //db_host
            if (strlen($db_host) < 1) {
                $errors[] = array('db_host' => array('status' => 'error', 'message' => 'You must have a database host that you can connect to. If this database server is hosted on the same machine, most often times "localhost" will work.'));
            }
        
            //db_database
            if (strlen($db_database) < 1) {
                $errors[] = array('db_database' => array('status' => 'error', 'message' => 'You must specify a database to connect to for hosting information important for the panels functionality. It is okay if the database is non existant yet as it will be created later.'));
            }
        
            //db_username
            if (strlen($db_username) < 1) {
                $errors[] = array('db_username' => array('status' => 'error', 'message' => 'You must specify a username and password for logging into the database server and by extension the database.'));
            }
        
            //db_password
            if (strlen($db_password) < 1) {
                $errors[] = array('db_password' => array('status' => 'error', 'message' => 'You must specify a valid password for the database user to login to the database on your database server.'));
            }
        
            //db_port
            if (strlen($db_port) < 1 || strlen($db_port) > 5) {
                $errors[] = array('db_port' => array('status' => 'error', 'message' => 'You must specify a valid port to use for communicating to and from the Database Server. Typically the default port used is 3306.'));
            }
        
            // Database Test Credentials Function
            if ($db_connection !== null && $db_host !== null && $db_username !== null && $db_password !== null && $db_port !== null) {
                try {
                    $database_test = databaseTestFunction($db_connection, $db_host, $db_username, $db_password, $db_database, $db_port);
                    
                    if ($database_test == "database_login_failure") {
                      //report connection broken
                        $errors[] = array('db_test' => array('status' => 'error', 'message' => 'Connection to the database server using the parameters you supplied did not work. Details are below:\n' . $database_test . '\n Correct these errors to continue installation.'));
                    } else if ($database_test == "connection_successful") {
                      //report connection works
                      //no action for now.
                      $dbReturn[] = array('db_test' => array('status' => 'success'));
                    } else if ($database_test == "unsupported_database_server") {
                        $errors[] = array('db_test' => array('status' => 'error', 'message' => 'We currently do not support the database server you have chosen.'));
                    }
                } catch (Exception $e) {
                    $errors[] = array('db_test' => array('status' => 'error', 'message' => 'db_connection_failed'));
                }
            }
        
            //bcrypt_rounds
            if (strlen($bcrypt_rounds) < 1) {
                $errors[] = array('brypt_rounds' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //broadcast_driver
            if (strlen($broadcast_driver) < 1) {
                $errors[] = array('broadcast_driver' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //cache_driver
            if (strlen($cache_driver) < 1) {
                $errors[] = array('cache_driver' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //filesystem_disk
            if (strlen($filesystem_disk) < 1) {
                $errors[] = array('filesystem_disk' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //queue_connection
            if (strlen($queue_connection) < 1) {
                $errors[]= array('queue_connection' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //session_driver
            if (strlen($session_driver) < 1) {
                $errors[] = array('session_driver' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //session_lifetime
            if (strlen($session_lifetime) < 1) {
                $errors[] = array('session_lifetime' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //memcached_host
            if (strlen($memcached_host) < 1) {
                $errors[] = array('memcached_host' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //mail_mailer
            if (strlen($mail_mailer) < 1) {
                $errors[] = array('mail_mailer' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //mail_host
            if (strlen($mail_host) < 1) {
                $errors[] = array('mail_host' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            }
            //mail_username
            if (strlen($mail_username) < 1) {
                $errors[] = array('mail_username' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //mail_port
            if (strlen($mail_port) < 1) {
                $errors[] = array('mail_port' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //mail_from_address
            if (strlen($mail_from_address) < 1) {
                $errors[] = array('mail_from_address' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 
            //mail_from_name
            if (strlen($mail_from_name) < 1) {
                $errors[] = array('mail_from_name' => array('status' => 'error', 'message' => 'This value cannot be left blank'));
            } 

            // disabled amazon web services for now (why do we even support this???)
            return $errors;
        }
        
        // Define all post stuffs.
        $app_name = $request->input('app_name');
        $app_env = $request->input('app_env');
        $app_key = $request->input('app_key');
        $app_debug = $request->input('app_debug');
        $app_url = $request->input('app_url');
        $master_api_key = $request->input('master_api_key');
        $use_captcha = $request->input('use_captcha');
        $captcha_vendor = $request->input('captcha_vendor');
        $google_captcha_key = $request->input('google_captcha_key');
        $google_captcha_secret = $request->input('google_captcha_secret');
        $use_discord = $request->input('use_discord');
        $discord_use_case = $request->input('discord_use_case');
        $discord_redirect_uri = $request->input('discord_redirect_uri');
        $discord_redirect_auth = $request->input('discord_redirect_auth');
        $master_admin_discord_id = $request->input('master_admin_discord_id');
        $master_admin_role_id = $request->input('master_admin_role_id');
        $log_channel = $request->input('log_channel');
        $log_deprecation_channel = $request->input('log_deprecation_channel');
        $log_level = $request->input('log_level');
        $db_connection = $request->input('db_connection');
        $db_host = $request->input('db_host');
        $db_username = $request->input('db_username');
        $db_password = $request->input('db_password');
        $db_database = $request->input('db_database');
        $db_port = $request->input('db_port');
        $broadcast_driver = $request->input('broadcast_driver');
        $filesystem_disk = $request->input('filesystem_disk');
        $queue_connection = $request->input('queue_connection');
        $session_driver = $request->input('session_driver');
        $session_lifetime = $request->input('session_lifetime');
        $memcached_host = $request->input('memcached_host');
        $mail_mailer = $request->input('mail_mailer');
        $mail_host = $request->input('mail_host');
        $mail_username = $request->input('mail_username');
        $mail_port = $request->input('mail_port');
        $mail_password = $request->input('mail_password');
        $mail_from_address = $request->input('mail_from_address');
        $mail_from_name = $request->input('mail_from_name');
        $cache_driver = $request->input('cache_driver');
        $bcrypt_rounds = $request->input('bcrypt_rounds');
        $response = validateThenGenerate($app_name, $app_env, $app_key, $app_debug, $app_url, $master_api_key, $use_captcha, $captcha_vendor, $google_captcha_key, $google_captcha_secret, $use_discord, $discord_use_case, $discord_redirect_uri, $discord_redirect_auth, $master_admin_discord_id, $master_admin_role_id, $log_channel, $log_deprecation_channel, $log_level, $db_connection, $db_host, $db_username, $db_password, $db_port, $db_database, $broadcast_driver, $cache_driver, $filesystem_disk, $queue_connection, $session_driver, $session_lifetime, $memcached_host, $mail_mailer, $mail_host, $mail_username, $mail_port, $mail_password, $mail_from_address, $mail_from_name, $bcrypt_rounds);
        $configData = [
            'app_name' => $request->input('app_name'),
            'app_env' => $request->input('app_env'),
            'app_key' => $request->input('app_key'),
            'app_debug' => $request->input('app_debug'),
            'app_url' => $request->input('app_url'),
            'master_api_key' => $request->input('master_api_key'),
            'use_captcha' => $request->input('use_captcha'),
            'captcha_vendor' => $request->input('captcha_vendor'),
            'google_captcha_key' => $request->input('google_captcha_key'),
            'google_captcha_secret' => $request->input('google_captcha_secret'),
            'use_discord' => $request->input('use_discord'),
            'discord_use_case' => $request->input('discord_use_case'),
            'discord_redirect_uri' => $request->input('discord_redirect_uri'),
            'discord_redirect_auth' => $request->input('discord_redirect_auth'),
            'master_admin_discord_id' => $request->input('master_admin_discord_id'),
            'master_admin_role_id' => $request->input('master_admin_role_id'),
            'log_channel' => $request->input('log_channel'),
            'log_deprecation_channel' => $request->input('log_deprecation_channel'),
            'log_level' => $request->input('log_level'),
            'db_connection' => $request->input('db_connection'),
            'db_host' => $request->input('db_host'),
            'db_username' => $request->input('db_username'),
            'db_password' => $request->input('db_password'),
            'db_database' => $request->input('db_database'),
            'db_port' => $request->input('db_port'),
            'broadcast_driver' => $request->input('broadcast_driver'),
            'filesystem_disk' => $request->input('filesystem_disk'),
            'queue_connection' => $request->input('queue_connection'),
            'session_driver' => $request->input('session_driver'),
            'session_lifetime' => $request->input('session_lifetime'),
            'memcached_host' => $request->input('memcached_host'),
            'mail_mailer' => $request->input('mail_mailer'),
            'mail_host' => $request->input('mail_host'),
            'mail_username' => $request->input('mail_username'),
            'mail_port' => $request->input('mail_port'),
            'mail_password' => $request->input('mail_password'),
            'mail_from_address' => $request->input('mail_from_address'),
            'mail_from_name' => $request->input('mail_from_name'),
            'cache_driver' => $request->input('cache_driver'),
            'bcrypt_rounds' => $request->input('bcrypt_rounds'),
        ];
        $responseErrors = concatErrors($response);
        if (isset($responseErrors['fields']['status']) && $responseErrors['fields']['status'] === 'success') {
            if (isset($responseErrors['db_test']['status']) && $responseErrors['db_test']['status'] === 'success') {
                //createEnvFileFunction();
                return response()->json($responseErrors);
            } else {
                return response()->json($responseErrors);
            }
        } else {
            return response()->json($responseErrors);
        }
    }
    private function createEnvFileFunction(array $configData) {
        $envContent = '';
        foreach ($configData as $key => $value) {
            $escapedValue = addslashes($value);
            $envContent .= "$key=\"$escapedValue\"\n";
        }
        $attempt2save = file_put_contents(base_path('.env'), $envContent);
        if ($attempt2save !== false) {
            echo 'File Written ' . realpath(base_path('.env'));
            return view('install.database', compact('data'));
        } else {
            echo 'Error Writting';
        }
    }
    private function installationComplete(): bool {
        $installerFile = 'installerController.php';
        
        redirect()->intended('DASHBOARD');
        unlink($installerFile); 
    }
    
}
