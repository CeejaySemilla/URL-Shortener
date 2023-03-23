<?php
// ============================================ BASE CONTROLLER ============================================
namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function login()
    {
        session()->destroy();
        if(array_key_exists('user_session', session()->get())){
            return redirect()->to('admin');
        }
        return view('login_page');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function admin()
    {
        if(array_key_exists('user_session', session()->get())){
            // $data = array();
            // $data['user'] = session()->get('user_session');
            $qturl_m = new \App\Models\CuteUrl();
            $user = session()->get('user_session');

            $data['user'] = $user;
            $data['urls'] = $qturl_m
                ->where('user_id', $user['id'])
                ->orderBy('dtc', 'desc')
                ->findAll();
            return view('admin_page', $data);
        }else{
            return redirect()->to('login');
        }
    }

    public function users()
    {
        if (array_key_exists('user_session', session()->get())) {

            $user_m = new \App\Models\UserAccount();

            $user = session()->get('user_session');

            if (array_key_exists('user_id', $this->request->getGet())) {

                $data['edit_user'] = $user_m->find($this->request->getGet('user_id'));
            }

            $data['user'] = $user;
            $data['users'] = $user_m
                ->where('id !=', $user['id'])
                ->orderBy('dtc', 'desc')
                ->findAll();
            return view('users_page', $data);
        } else {
            return redirect()->to('login');
        }
    }

    public function registration(){
        return view('registration_page');
    }

    public function process($mode){

        switch($mode) {

            case 'login':{
                $user_m = new \App\Models\UserAccount();

                $username = $this->request->getPost('username');
                $password = $this->request->getPost('pass');
        
                $users_data = $user_m
                    ->where('username', $username) //(column name, variable)
                    ->findAll();
        
                if(count($users_data) > 0){
                    session()->set('user_session', $users_data[0]);
                    return redirect()->to('admin');
                    $verify = password_verify($password, $users_data[0]['password']);
                    
                    if($verify){
                        session()->set('user_session', $users_data[0]);
                        return redirect()->to('admin');
                    }else{
                        return redirect()->to('login');
                    }

                }else{
                    return redirect()->to('login');
                }

                //=================CHECKER=================
                // $result = $user_m->where('username', $username)->first();
                // print_r( $result );
                
            }


            case 'registration': {
                
                $user_m = new \App\Models\UserAccount(); //"Instantiate"

                $fullName = $this->request->getPost('regFullName');
                $regUsername = $this->request->getPost('regUsername');
                $regPassword = $this->request->getPost('regPassword');
                $verifyPassword = $this->request->getPost('verifyPassword');

                if ($regPassword === $verifyPassword){
                    $user_m->save([
                        'full_name' => $fullName, //column name, variable
                        'username' => $regUsername,
                        'password' => password_hash($regPassword, PASSWORD_ARGON2ID),
                    ]);
                }
                
                return redirect()->to('admin');

                // $data = [
                //     'full_name' => $fullName, //column name, variable
                //     'username' => $regUsername,
                //     'password' => password_hash($regPassword, PASSWORD_ARGON2ID),
                // ];

                //return redirect()->to('process/registration');
            }
            break;

            case 'update_user': {

                $user_m = new \App\Models\UserAccount();

                $id = $this->request->getPost('id');
                $full_name = $this->request->getPost('full_name');
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('pass');

                $edit['id'] = $id;
                $edit['full_name'] = $full_name;
                $edit['username'] = $username;

                if (!empty($password)) {
                    $edit['password'] = password_hash($password, PASSWORD_ARGON2ID);
                }

                $user_m->save($edit);

                return redirect()->to('users');
            }
            break;
            case 'delete_user': {

                    $user_m = new \App\Models\UserAccount();
                    $user_id = $this->request->getGet('user_id');

                    $user_m->delete($user_id);
                    return redirect()->to('users');
                }
                break;
            case 'add_url': {
                // $qturl_m = new \App\Models\CuteUrl();
                // $user = session()->get('user_session');
                // $source_url = $this->request->getPost('source_url');
                // $custom = $this->request->getPost('custom');

                // switch (true) {
                //     case empty($source_url):
                //         return redirect()->to('admin');
                //         break;
                //     case !preg_match("~^(?:f|ht)tps?://~i", $source_url):
                //         $source_url = "http://" . $source_url;
                //         break;
                //     case empty($custom):
                //         do {
                //             $custom = substr(md5(uniqid(rand(), true)), 0, 6);
                //             $existing_custom = $qturl_m->where('custom', $custom)->countAllResults();
                //         } while ($existing_custom > 0);
                //         break;
                //     }
                    // your code here 😏

                    // VERSION 1
                    // $qturl_m = new \App\Models\CuteUrl();
                    // $user = session()->get('user_session');

                    // $source_url = $this->request->getPost('source_url');
                    // $short_code = substr(md5($source_url . time()), 0, 8);


                    // $qturl_m->save([
                    //     'user_id' => $user['id'],
                    //     'source_url' => $source_url,
                    //     'custom' => $short_code 
                    // ]);
                    // return base_url($source_url);

                    
                    // $qturl_m = new \App\Models\CuteUrl();
                    // $user = session()->get('user_session');
                    
                    // $source_url = $this->request->getPost('source_url'); //Long URL
                    // $custom_url = substr(md5($source_url . time()), 0, 8);
                    
                    // $qturl_m->save([
                        //     'source_url' => $source_url,
                        //     'custom' => $custom_url
                        // ]);
                        // return redirect()->to('admin');

                        $qturl_m = new \App\Models\CuteUrl();
                        $user = session()->get('user_session');
                    
                        $source_url = $this->request->getPost('source_url');
                        $custom = $this->request->getPost('custom');
                    
                        if (empty($source_url)) {
                            return redirect()->to('admin');
                        }
                    
                        if (!preg_match("~^(?:f|ht)tps?://~i", $source_url)) {
                            $source_url = "http://" . $source_url;
                        }
                    
                        if (empty($custom)) {
                            do {
                                $custom = substr(md5(uniqid(rand(), true)), 0, 8);
                                $existing_custom = $qturl_m->where('custom', $custom)->countAllResults();
                            } while ($existing_custom > 0);
                        }
                    
                        $qturl_m->save([
                            'user_id' => $user['id'],
                            'source_url' => $source_url,
                            'custom' => $custom
                        ]);
                        return redirect()->to('admin');
                    
                    }
            default:
                return redirect()->to('login');
        }
    }
    public function shortcut_url($custom)
    {
        $qturl_m = new \App\Models\CuteUrl();
        $qturl_d = $qturl_m->where('custom', $custom)->findAll();
        if (count($qturl_d) > 0) {
            return redirect()->to($qturl_d[0]['source_url']);
        } else {
            echo "URL no longer exists.";
        }
    }
    
}
