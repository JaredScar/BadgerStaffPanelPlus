<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Sessions;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class InstallController extends Controller {

    // Display the content for each installation step
    public function determineVersion() {
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
        $data['view_name'] = 'installer';
        $data['customize'] = false;
        return view('/install/welcome', compact('laravelV', 'phpV', 'data'));
    }
    public function showPage($page) {
        switch ($page) {
            case 'welcome':
                $this->executeWelcomeFunctions();
                return view('install.welcome');
            case 'agreement':
                $this->executeAgreementFunctions();
                return view('install.agreement');
            case 'config':
                $this->executeConfigFunctions();
                return view('install.config');
            case 'database':
                $this->executeDatabaseFunctions();
                return view('install.database');
            case 'adminuser':
                $this->executeAdminuserFunctions();
                return view('install.adminuser');
            case 'discord':
                $this->executeDiscordFunctions();
                return view('install.completed');
            case 'groups':
                $this->executeGroupFunctions();
                return view('install.completed');
            case 'Complete':
                $this->executeCompleteFunctions();
                return view('install.completed');
            default:
                abort(404);
        }
    }

    // Move to the next page
    public function moveToNextPage(Request $request) {
        $currentPage = $request->input('currentPage');

        // Determine the next page
        $nextPage = $this->determineNextPage($currentPage);

        // Redirect to the next page or stay on the current page with errors
        if ($nextPage !== null) {
            return redirect()->route('installer', ['page' => $nextPage]);
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

    private function executeAgreementFunctions() {
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
    private function installationComplete(): bool {
        $installerFile = 'installerController.php';
        
        redirect()->intended('DASHBOARD');
        unlink($installerFile);
    }
}
