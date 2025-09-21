<?php

class App extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->database();

        $this->load->library("parser");
        $this->load->library("Emails");
        $this->load->library("Form_validation");

        $this->load->helper("url");
        $this->load->helper('form');
        $this->load->helper('text');

        $this->load->helper("Post_helper");
        $this->load->helper("Date_helper");

        $this->load->model('Post');
        $this->load->model('Category');
    }

    public function login() {
        $view["body"] = $this->load->view("app/login", NULL, TRUE);
        $this->parser->parse("admin/template/body_format_2", $view);
    }

    public function ajax_attempt_login() {
        if ($this->input->is_ajax_request()) {
            // Allow this page to be an accepted login page
            $this->config->set_item('allowed_pages_for_login', ['app/ajax_attempt_login']);

            // Make sure we aren't redirecting after a successful login
            $this->authentication->redirect_after_login = FALSE;

            // Do the login attempt
            $this->auth_data = $this->authentication->user_status(0);

            // Set user variables if successful login
            if ($this->auth_data)
                $this->_set_user_variables();

            // Call the post auth hook
            $this->post_auth_hook();

            // Login attempt was successful
            if ($this->auth_data) {
                echo json_encode([
                    'status' => 1,
                    'user_id' => $this->auth_user_id,
                    'username' => $this->auth_username,
                    'level' => $this->auth_level,
                    'role' => $this->auth_role,
                    'email' => $this->auth_email
                ]);
            }

            // Login attempt not successful
            else {
                $this->tokens->name = config_item('login_token_name');

                $on_hold = (
                        $this->authentication->on_hold === TRUE OR
                        $this->authentication->current_hold_status()
                        ) ? 1 : 0;

                echo json_encode([
                    'status' => 0,
                    'count' => $this->authentication->login_errors_count,
                    'on_hold' => $on_hold,
                    'token' => $this->tokens->token()
                ]);
            }
        }

        // Show 404 if not AJAX
        else {
            show_404();
        }
    }

    public function logout() {
        // Log the logout action
        $username = $this->session->userdata('username');
        log_message('info', 'User ' . $username . ' logged out');
        
        // Perform logout
        $this->authentication->logout();
        $this->session->sess_destroy();

        // Set redirect protocol
        $redirect_protocol = USE_SSL ? 'https' : NULL;

        // Redirect to login page with logout parameter
        redirect(site_url(LOGIN_PAGE . '?' . AUTH_LOGOUT_PARAM . '=1', $redirect_protocol));
    }

    public function register() {

        $data["first_name"] = $data["last_name"] = $data["username"] = $data["email"] = $data["user_role"] = "";

        if ($this->input->server('REQUEST_METHOD') == "POST") {

                $this->form_validation->set_rules('username', 'username', 'max_length[50]|is_unique[' . config_item('user_table') . '.username]|required');
            $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|is_unique[' . config_item('user_table') . '.email]');
            $this->form_validation->set_rules('passwd', 'password', 'min_length[8]|trim|required|max_length[72]|callback_validate_passwd');
            $this->form_validation->set_rules('first_name', 'first name', 'max_length[50]|required');
            $this->form_validation->set_rules('last_name', 'last name', 'max_length[50]|required');
            $this->form_validation->set_rules('user_role', 'user role', 'required|in_list[3,6]');
            $this->form_validation->set_rules('terms', 'terms and conditions', 'required');
            $this->form_validation->set_message('is_unique', 'The %s is already registered');
            $this->form_validation->set_message('in_list', 'Please select a valid user role');

                $data['first_name'] = $this->input->post("first_name");
                $data['last_name'] = $this->input->post("last_name");
            $data['username'] = $this->input->post("username");
            $data['email'] = $this->input->post("email");
            $data['user_role'] = $this->input->post("user_role");

            if ($this->form_validation->run()) {

                $save = array(
                    'first_name' => $this->input->post("first_name"),
                    'last_name' => $this->input->post("last_name"),
                    'username' => $this->input->post("username"),
                    'email' => $this->input->post("email"),
                    'passwd' => $this->authentication->hash_passwd($this->input->post("passwd")),
                    'user_id' => $this->User->get_unused_id(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'auth_level' => $this->input->post("user_role"), // 3 for Cleaner, 6 for Host
                    'banned' => '1', // Auto-ban new users until admin review
                    'pending_verification' => '1', // Mark as pending verification
                    'email_verified' => '0', // Email not verified yet
                    'locked' => '0', // Account not locked
                    'failed_logins' => 0, // No failed logins
                    'login_count' => 0, // No logins yet
                    'last_ip' => $this->input->ip_address(), // Current IP
                    'created_by' => 0, // System created
                    'modified_by' => 0, // System modified
                    'avatar' => '', // No avatar yet
                    'passwd_recovery_code' => NULL, // No recovery code
                    'passwd_recovery_date' => NULL, // No recovery date
                    'passwd_modified_at' => date('Y-m-d H:i:s'), // Password modified now
                    'last_login' => NULL, // No last login
                    'modified_at' => date('Y-m-d H:i:s') // Modified now
                );
                
                // Add optional fields if they exist in the database
                $columns = $this->db->list_fields('users');
                
                if (in_array('banned_reason', $columns)) {
                    $save['banned_reason'] = 'Account pending admin review';
                }
                if (in_array('banned_at', $columns)) {
                    $save['banned_at'] = date('Y-m-d H:i:s');
                }
                if (in_array('banned_by', $columns)) {
                    $save['banned_by'] = 'system';
                }

                // Attempt to insert user using direct database insert
                $this->db->insert('users', $save);
                $insert_result = $this->db->affected_rows();
                
                if ($insert_result > 0) {
                    // Log the registration
                    log_message('info', 'New user registered: ' . $this->input->post("username") . ' (Role: ' . $this->input->post("user_role") . ') - Account banned pending review');
                    
                    // Redirect to login with success parameter (no flash data needed)
                    redirect("/login?registered=1");
                } else {
                    // Log the error for debugging
                    log_message('error', 'User registration failed for: ' . $this->input->post("username") . ' - Database error: ' . $this->db->last_query());
                    $data['registration_error'] = "Registration failed. Please try again.";
                }
            }
        }

        $view['body'] = $this->load->view("app/register", $data, TRUE);
        $this->parser->parse("admin/template/body_format_2", $view);
    }

    /* Perfil */

    public function profile() {

        $this->init_session_auto(1);
        $this->load->helper('Breadcrumb_helper');

        $data['user'] = $this->User->find($this->session->userdata('id'));

        $this->form_validation->set_rules('old_pass', 'Contraseña actual', 'required|callback_validate_same_passwd');
        $this->form_validation->set_rules('new_pass', 'Contraseña nueva', 'required|min_length[8]|max_length[72]|callback_validate_passwd');
        $this->form_validation->set_rules('new_pass_veri', 'Repita la nueva contraseña', 'required|matches[new_pass]');

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->form_validation->run()) {
                //formulario valido

                $save = array(
                    'passwd' => $this->authentication->hash_passwd($this->input->post('new_pass'))
                );

                $this->User->update($this->session->userdata('id'), $save);
                $this->session->sess_destroy();
                $this->session->set_flashdata('text', 'Contraseña actualizada');
                $this->session->set_flashdata('type', 'danger');
                redirect('/login');
            }
        }

        $view["body"] = $this->load->view("app/profile", $data, TRUE);

        if ($this->session->userdata("auth_level") == 9) {
            $view["title"] = 'Perfil';
            $view['breadcrumb'] = breadcrumb_admin("profile");
            $this->parser->parse("admin/template/body", $view);
        } else {
            $this->parser->parse("blog/template/body", $view);
        }
    }

    /* recuperacion credenciales */

    /**
     * User recovery form
     */
    public function recover() {

        /// If IP or posted email is on hold, display message
        if ($on_hold = $this->authentication->current_hold_status(TRUE)) {
            $view_data['disabled'] = 1;
        } else {
            // If the form post looks good
            if ($this->tokens->match && $this->input->post('email')) {
                if ($user_data = $this->User->get_recovery_data($this->input->post('email'))) {
                    // Check if user is banned
                    if ($user_data->banned == '1') {
                        // Log an error if banned
                        $this->authentication->log_error($this->input->post('email', TRUE));

                        // Show special message for banned user
                        $view_data['banned'] = 1;
                    } else {
                        /**
                         * Use the authentication libraries salt generator for a random string
                         * that will be hashed and stored as the password recovery key.
                         * Method is called 4 times for a 88 character string, and then
                         * trimmed to 72 characters
                         */
                        $recovery_code = substr($this->authentication->random_salt()
                                . $this->authentication->random_salt()
                                . $this->authentication->random_salt()
                                . $this->authentication->random_salt(), 0, 72);

                        // Update user record with recovery code and time
                        $this->User->update_user_raw_data(
                                $user_data->user_id, [
                            'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
                            'passwd_recovery_date' => date('Y-m-d H:i:s')
                                ]
                        );

                        // Set the link protocol
                        $link_protocol = USE_SSL ? 'https' : NULL;

                        // Set URI of link
                        $link_uri = 'app/recovery_verification/' . $user_data->user_id . '/' . $recovery_code;

                        $view_data['special_link'] = anchor(
                                site_url($link_uri, $link_protocol), site_url($link_uri, $link_protocol), 'target ="_blank"'
                        );

                        $this->emails->recover_account($link_uri, $this->input->post('email'));

                        $view_data['confirmation'] = 1;
                    }
                }

                // There was no match, log an error, and display a message
                else {
                    // Log the error
                    $this->authentication->log_error($this->input->post('email', TRUE));

                    $view_data['no_match'] = 1;
                }
            }
        }

        $view['body'] = $this->load->view("app/recover_form", ( isset($view_data) ) ? $view_data : '', TRUE);
        $this->parser->parse("admin/template/body_format_2", $view);
    }

    // --------------------------------------------------------------

    /**
     * Verification of a user by email for recovery
     * 
     * @param  int     the user ID
     * @param  string  the passwd recovery code
     */
    public function recovery_verification($user_id = '', $recovery_code = '') {
        /// If IP is on hold, display message
        if ($on_hold = $this->authentication->current_hold_status(TRUE)) {
            $view_data['disabled'] = 1;
        } else {

            if (
            /**
             * Make sure that $user_id is a number and less 
             * than or equal to 10 characters long
             */
                    is_numeric($user_id) && strlen($user_id) <= 10 &&
                    /**
                     * Make sure that $recovery code is exactly 72 characters long
                     */
                    strlen($recovery_code) == 72 &&
                    /**
                     * Try to get a hashed password recovery 
                     * code and user salt for the user.
                     */
                    $recovery_data = $this->User->get_recovery_verification_data($user_id)) {
                /**
                 * Check that the recovery code from the 
                 * email matches the hashed recovery code.
                 */
                if ($recovery_data->passwd_recovery_code == $this->authentication->check_passwd($recovery_data->passwd_recovery_code, $recovery_code)) {
                    $view_data['user_id'] = $user_id;
                    $view_data['username'] = $recovery_data->username;
                    $view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
                }

                // Link is bad so show message
                else {
                    $view_data['recovery_error'] = 1;

                    // Log an error
                    $this->authentication->log_error('');
                }
            }

            // Link is bad so show message
            else {
                $view_data['recovery_error'] = 1;

                // Log an error
                $this->authentication->log_error('');
            }

            /**
             * If form submission is attempting to change password 
             */
            if ($this->tokens->match) {
                $this->User->recovery_password_change();
            }
        }

        $view['body'] = $this->load->view("app/choose_password_form", ( isset($view_data) ) ? $view_data : '', TRUE);
        $this->parser->parse("admin/template/body_format_2", $view);
    }

    /* Upload Perfil */

    public function load_avatar() {

        $this->avatar_upload();
        $this->session->set_flashdata('type', 'success');
        $this->session->set_flashdata('text', 'Avatar cambiado con exito.');
        redirect('/app/profile');
    }

    /* Subida del avatar */

    private function avatar_upload() {

        $id = $this->session->userdata('id');

        $image = 'image';
        $config['upload_path'] = 'uploads/user/';
        $config['file_name'] = 'imagen_' . $id;
        $config['allowed_types'] = "jpg|jpeg|png";
        $config['overwrite'] = TRUE;

        $this->load->library('upload', $config);

        // ocurrio un error
        if (!$this->upload->do_upload($image)) {
            $this->session->set_flashdata('type', 'danger');
            $this->session->set_flashdata('text', $this->upload->display_errors());
            return;
        }

        // informacion hacerca de la subida
        $data = $this->upload->data();
        $save = array('avatar' => 'imagen_' . $id . $data['file_ext']);
        // actualizo la url
        $this->User->update($id, $save);
        $this->session->set_userdata('avatar', $save['avatar']);
        $this->resize_avatar($data['full_path'], $save['avatar']);
    }

    private function resize_avatar($ruta, $nombre) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $ruta;
        $config['new_image'] = 'uploads/avatar/user/' . $nombre;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 300;
        $config['height'] = 300;

        $this->load->library('image_lib', $config);

        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
    }

}
