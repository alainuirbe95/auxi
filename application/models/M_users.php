<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * M_users Model
 *
 * Model for handling user data operations
 *
 * @package     EasyClean
 * @author      EasyClean Team
 * @copyright   Copyright (c) 2024, EasyClean
 */
class M_users extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all users from the users table
     *
     * @return  array   Array of user objects
     */
    public function get_all_users() {
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Get users with pagination
     *
     * @param   int     $limit      Number of records to return
     * @param   int     $offset     Offset for pagination
     * @return  array   Array of user objects
     */
    public function get_users_paginated($limit = 10, $offset = 0) {
        $query = $this->db->limit($limit, $offset)->get('users');
        return $query->result();
    }

    /**
     * Get total count of users
     *
     * @return  int     Total number of users
     */
    public function get_users_count() {
        return $this->db->count_all('users');
    }

    /**
     * Get user by ID
     *
     * @param   int     $user_id    User ID to search for
     * @return  object  User object or FALSE if not found
     */
    public function get_user_by_id($user_id) {
        $query = $this->db->where('user_id', $user_id)->get('users');
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return FALSE;
    }

    /**
     * Get users by status (active, inactive, banned, etc.)
     *
     * @param   string  $status     Status to filter by
     * @return  array   Array of user objects
     */
    public function get_users_by_status($status) {
        $query = $this->db->where('banned', $status)->get('users');
        return $query->result();
    }

    /**
     * Search users by name or email
     *
     * @param   string  $search_term    Search term
     * @return  array   Array of user objects
     */
    public function search_users($search_term) {
        $this->db->like('username', $search_term);
        $this->db->or_like('email', $search_term);
        $this->db->or_like('first_name', $search_term);
        $this->db->or_like('last_name', $search_term);
        
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Get users with specific fields only
     *
     * @param   array   $fields     Array of field names to select
     * @return  array   Array of user objects with selected fields only
     */
    public function get_users_with_fields($fields = array()) {
        if (empty($fields)) {
            $fields = array('user_id', 'username', 'email', 'first_name', 'last_name', 'banned');
        }
        
        $this->db->select($fields);
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Get active users only
     *
     * @return  array   Array of active user objects
     */
    public function get_active_users() {
        $query = $this->db->where('banned', '0')->get('users');
        return $query->result();
    }


    /**
     * Get recent users (last N days)
     *
     * @param   int     $days       Number of days to look back
     * @return  array   Array of user objects
     */
    public function get_recent_users($days = 30) {
        $date_threshold = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $query = $this->db->where('created_at >=', $date_threshold)->get('users');
        return $query->result();
    }

    /**
     * Get detailed user information for admin view
     *
     * @param   int     $user_id    User ID to get details for
     * @return  object  Detailed user object or FALSE if not found
     */
    public function get_user_details($user_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return FALSE;
    }

    /**
     * Get user with additional statistics
     *
     * @param   int     $user_id    User ID
     * @return  object  User object with statistics or FALSE
     */
    public function get_user_with_stats($user_id) {
        $user = $this->get_user_by_id($user_id);
        
        if ($user) {
            // Add calculated fields
            $user->days_since_registration = 0;
            $user->days_since_last_login = 0;
            
            if (!empty($user->created_at)) {
                $user->days_since_registration = floor((time() - strtotime($user->created_at)) / (60 * 60 * 24));
            }
            
            if (!empty($user->last_login)) {
                $user->days_since_last_login = floor((time() - strtotime($user->last_login)) / (60 * 60 * 24));
            }
            
            // Add status information
            $user->is_active = ($user->banned ?? '0') == '0';
            $user->is_verified = ($user->email_verified ?? '0') == '1';
            $user->is_locked = ($user->locked ?? '0') == '1';
            
                // Add user level name
                $user_level = $user->auth_level ?? '3';
                switch($user_level) {
                    case '9':
                        $user->level_name = 'Administrator';
                        break;
                    case '6':
                        $user->level_name = 'Host';
                        break;
                    case '3':
                        $user->level_name = 'Cleaner';
                        break;
                    default:
                        $user->level_name = 'Level ' . $user_level;
                        break;
                }
        }
        
        return $user;
    }

    /**
     * Create a new user
     *
     * @param   array   $user_data  Array of user data
     * @return  int     User ID if successful, FALSE if failed
     */
    public function create_user($user_data) {
        // Generate unique user ID
        $user_data['user_id'] = $this->get_unused_id();
        
        // Hash the password
        $user_data['passwd'] = $this->hash_password($user_data['passwd']);
        
        // Set default values
        $user_data['banned'] = $user_data['banned'] ?? '0';
        $user_data['email_verified'] = $user_data['email_verified'] ?? '0';
        $user_data['login_count'] = $user_data['login_count'] ?? 0;
        $user_data['failed_logins'] = $user_data['failed_logins'] ?? 0;
        $user_data['locked'] = $user_data['locked'] ?? '0';
        
        // Set created/modified timestamps
        $user_data['created_at'] = $user_data['created_at'] ?? date('Y-m-d H:i:s');
        $user_data['modified_at'] = date('Y-m-d H:i:s');
        
        // Insert the user
        $this->db->insert('users', $user_data);
        
        if ($this->db->affected_rows() == 1) {
            return $user_data['user_id'];
        }
        
        return FALSE;
    }

    /**
     * Hash password using the same method as Community Auth
     *
     * @param   string  $password   Plain text password
     * @return  string  Hashed password
     */
    private function hash_password($password) {
        // Load the authentication library if not already loaded
        if (!isset($this->authentication)) {
            $this->load->library('authentication');
        }
        
        return $this->authentication->hash_passwd($password);
    }

    /**
     * Get an unused ID for user creation
     *
     * @return  int between 1200 and 4294967295
     */
    private function get_unused_id() {
        // Create a random user id between 1200 and 4294967295
        $random_unique_int = 2147483648 + mt_rand(-2147482448, 2147483647);

        // Make sure the random user_id isn't already in use
        $query = $this->db->where('user_id', $random_unique_int)->get('users');

        if ($query->num_rows() > 0) {
            $query->free_result();

            // If the random user_id is already in use, try again
            return $this->get_unused_id();
        }

        return $random_unique_int;
    }

    /**
     * Delete a user
     *
     * @param   int     $user_id    User ID to delete
     * @return  bool    TRUE if successful, FALSE if failed
     */
    public function delete_user($user_id) {
        // Check if user exists
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return FALSE;
        }
        
        // Delete the user
        $this->db->where('user_id', $user_id);
        $result = $this->db->delete('users');
        
        return $result && $this->db->affected_rows() > 0;
    }

    /**
     * Ban a user
     *
     * @param   int     $user_id    User ID to ban
     * @return  bool    TRUE if successful, FALSE if failed
     */
    public function ban_user($user_id) {
        // Check if user exists
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return FALSE;
        }
        
        // Update user to banned status
        $data = array(
            'banned' => '1',
            'modified_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('user_id', $user_id);
        $result = $this->db->update('users', $data);
        
        return $result && $this->db->affected_rows() > 0;
    }

    /**
     * Unban a user
     *
     * @param   int     $user_id    User ID to unban
     * @return  bool    TRUE if successful, FALSE if failed
     */
    public function unban_user($user_id) {
        // Check if user exists
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return FALSE;
        }
        
        // Update user to unbanned status
        $data = array(
            'banned' => '0',
            'modified_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('user_id', $user_id);
        $result = $this->db->update('users', $data);
        
        return $result && $this->db->affected_rows() > 0;
    }

    /**
     * Update user data
     *
     * @param   int     $user_id    User ID to update
     * @param   array   $user_data  Array of user data to update
     * @return  bool    TRUE if successful, FALSE if failed
     */
    public function update_user($user_id, $user_data) {
        // Check if user exists
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return FALSE;
        }
        
        // Update the user
        $this->db->where('user_id', $user_id);
        $result = $this->db->update('users', $user_data);
        
        // Debug: Log the database query and result
        log_message('debug', 'Database update query: ' . $this->db->last_query());
        log_message('debug', 'Database affected rows: ' . $this->db->affected_rows());
        log_message('debug', 'Database update result: ' . ($result ? 'TRUE' : 'FALSE'));
        
        return $result && $this->db->affected_rows() > 0;
    }

    /**
     * Get user by email
     *
     * @param   string  $email      Email address to search for
     * @return  object  User object or FALSE if not found
     */
    public function get_user_by_email($email) {
        $query = $this->db->where('email', $email)->get('users');
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return FALSE;
    }

    /**
     * Get users with advanced filtering, searching, and pagination
     *
     * @param   array   $params     Filter parameters
     * @return  object  Object containing users and pagination info
     */
    public function get_users_advanced($params = array()) {
        // Default parameters
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $limit = isset($params['limit']) ? (int)$params['limit'] : 25;
        $search = isset($params['search']) ? $params['search'] : '';
        $sort_by = isset($params['sort_by']) ? $params['sort_by'] : 'created_at';
        $sort_order = isset($params['sort_order']) ? $params['sort_order'] : 'DESC';
        $status_filter = isset($params['status']) ? $params['status'] : '';
        $level_filter = isset($params['level']) ? $params['level'] : '';
        $verified_filter = isset($params['verified']) ? $params['verified'] : '';
        
        // Calculate offset
        $offset = ($page - 1) * $limit;
        
        // Start building query
        $this->db->select('*');
        
        // Apply search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('username', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('CONCAT(first_name, " ", last_name)', $search, 'both', FALSE);
            $this->db->group_end();
        }
        
        // Apply status filter (active/banned/pending)
        if ($status_filter !== '') {
            if ($status_filter === 'pending') {
                // For pending users, check pending_verification
                $this->db->where('pending_verification', '1');
            } else {
                // For active/banned, check banned status
                $this->db->where('banned', $status_filter);
            }
        }
        
        // Apply level filter
        if ($level_filter !== '') {
            $this->db->where('auth_level', $level_filter);
        }
        
        // Apply email verification filter
        if ($verified_filter !== '') {
            $this->db->where('email_verified', $verified_filter);
        }
        
        // Get total count for pagination
        $total_query = clone $this->db;
        $total_records = $total_query->count_all_results('users', FALSE);
        
        // Apply sorting
        $allowed_sort_fields = array('user_id', 'username', 'email', 'first_name', 'last_name', 'auth_level', 'banned', 'email_verified', 'created_at', 'last_login');
        if (in_array($sort_by, $allowed_sort_fields)) {
            $this->db->order_by($sort_by, $sort_order);
        }
        
        // Apply pagination
        $this->db->limit($limit, $offset);
        
        // Execute query
        $query = $this->db->get('users');
        $users = $query->result();
        
        // Calculate pagination info
        $total_pages = ceil($total_records / $limit);
        
        return (object) array(
            'users' => $users,
            'pagination' => array(
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_records' => $total_records,
                'limit' => $limit,
                'offset' => $offset,
                'has_next' => $page < $total_pages,
                'has_prev' => $page > 1
            )
        );
    }

    /**
     * Generate a temporary password for admin-initiated reset
     * 
     * @param int $user_id
     * @return string|false Temporary password or false on failure
     */
    public function generate_temp_password($user_id) {
        // Check if user exists and is not banned
        $user = $this->get_user_by_id($user_id);
        if (!$user || $user->banned == '1') {
            return false;
        }

        // Generate a secure temporary password
        $temp_password = $this->generate_secure_temp_password();
        
        // Hash the temporary password
        $hashed_temp_password = $this->hash_password($temp_password);
        
        // Update user record with temporary password (temporarily without new fields)
        $update_data = array(
            'passwd' => $hashed_temp_password,
            'modified_by' => $this->session->userdata('user_id') ?: NULL
        );
        
        $this->db->where('user_id', $user_id);
        if ($this->db->update('users', $update_data)) {
            return $temp_password;
        }
        
        return false;
    }

    /**
     * Generate a secure temporary password
     * 
     * @return string
     */
    private function generate_secure_temp_password() {
        // Generate a 12-character password with mixed case, numbers, and special chars
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        // Ensure at least one character from each category
        $password .= 'abcdefghijklmnopqrstuvwxyz'[rand(0, 25)]; // lowercase
        $password .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[rand(0, 25)]; // uppercase
        $password .= '0123456789'[rand(0, 9)]; // number
        $password .= '!@#$%^&*'[rand(0, 7)]; // special char
        
        // Fill remaining characters randomly
        for ($i = 4; $i < 12; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        // Shuffle the password
        return str_shuffle($password);
    }


    /**
     * Check if user needs to change password on next login
     * 
     * @param int $user_id
     * @return bool
     */
    public function needs_password_change($user_id) {
        // Temporarily disabled until database fields are added
        return false;
    }

    /**
     * Clear password force change flag
     * 
     * @param int $user_id
     * @return bool
     */
    public function clear_password_force_change($user_id) {
        // Temporarily disabled until database fields are added
        return true;
    }

    /**
     * Get pending users (users with pending_verification = 1)
     */
    public function get_pending_users() {
        $this->db->reset_query();
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('pending_verification', '1');
        $this->db->where('rejected', '0'); // Exclude rejected users
        $this->db->order_by('created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get rejected users (users with rejected = 1)
     */
    public function get_rejected_users() {
        $this->db->reset_query();
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('rejected', '1');
        $this->db->order_by('rejected_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Reject a pending user (permanently ban with rejection reason)
     */
    public function reject_user($user_id) {
        // First, check if the new rejection columns exist
        $columns = $this->db->list_fields('users');
        
        $update_data = array(
            'banned' => '1',
            'pending_verification' => '0'
        );
        
        // Add rejection fields if they exist
        if (in_array('rejected', $columns)) {
            $update_data['rejected'] = '1';
        }
        if (in_array('rejected_at', $columns)) {
            $update_data['rejected_at'] = date('Y-m-d H:i:s');
        }
        if (in_array('rejected_by', $columns)) {
            $update_data['rejected_by'] = $this->session->userdata('username') ?: 'admin';
        }
        
        // Add other optional fields if they exist in the database
        if (in_array('banned_reason', $columns)) {
            $update_data['banned_reason'] = 'Account rejected by admin';
        }
        if (in_array('banned_at', $columns)) {
            $update_data['banned_at'] = date('Y-m-d H:i:s');
        }
        if (in_array('banned_by', $columns)) {
            $update_data['banned_by'] = $this->session->userdata('user_id') ?: 'admin';
        }
        if (in_array('modified_by', $columns)) {
            $update_data['modified_by'] = $this->session->userdata('user_id') ?: 'admin';
        }
        
        $this->db->where('user_id', $user_id);
        $result = $this->db->update('users', $update_data);
        
        // Log the update for debugging
        log_message('info', 'Reject user update data: ' . json_encode($update_data));
        log_message('info', 'Available columns: ' . json_encode($columns));
        log_message('info', 'Update result: ' . ($result ? 'success' : 'failed'));
        
        return $result;
    }

    /**
     * Restore a rejected user (unban and allow them to reapply)
     */
    public function restore_user($user_id) {
        $update_data = array(
            'banned' => '0',
            'rejected' => '0',
            'rejected_at' => NULL,
            'rejected_by' => NULL
        );
        
        // Add optional fields if they exist in the database
        $columns = $this->db->list_fields('users');
        
        if (in_array('banned_reason', $columns)) {
            $update_data['banned_reason'] = NULL;
        }
        if (in_array('banned_at', $columns)) {
            $update_data['banned_at'] = NULL;
        }
        if (in_array('banned_by', $columns)) {
            $update_data['banned_by'] = NULL;
        }
        if (in_array('modified_by', $columns)) {
            $update_data['modified_by'] = $this->session->userdata('user_id') ?: 'admin';
        }
        
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $update_data);
    }

    /**
     * Get user statistics for dashboard
     *
     * @return  object  User statistics
     */
    public function get_user_statistics() {
        $stats = array();
        
        // Total users
        $stats['total_users'] = $this->db->count_all('users');
        
        // Active users
        $this->db->reset_query();
        $stats['active_users'] = $this->db->where('banned', '0')->count_all_results('users', FALSE);
        
        // Banned users
        $this->db->reset_query();
        $stats['banned_users'] = $this->db->where('banned', '1')->count_all_results('users', FALSE);
        
        // Verified users
        $this->db->reset_query();
        $stats['verified_users'] = $this->db->where('email_verified', '1')->count_all_results('users', FALSE);
        
        // Users by level
        $this->db->reset_query();
        $level_stats = $this->db->select('auth_level, COUNT(*) as count')
                               ->group_by('auth_level')
                               ->get('users')
                               ->result();
        
        $stats['by_level'] = array();
        foreach ($level_stats as $level_stat) {
            $stats['by_level'][$level_stat->auth_level] = $level_stat->count;
        }
        
        // Recent users (last 30 days)
        $this->db->reset_query();
        $stats['recent_users'] = $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                                         ->count_all_results('users', FALSE);
        
        return (object) $stats;
    }

    /**
     * Get users by date range
     *
     * @param   string  $start_date     Start date (Y-m-d)
     * @param   string  $end_date       End date (Y-m-d)
     * @param   int     $limit          Number of records to return
     * @return  array   Array of user objects
     */
    public function get_users_by_date_range($start_date, $end_date, $limit = 100) {
        $this->db->where('created_at >=', $start_date . ' 00:00:00');
        $this->db->where('created_at <=', $end_date . ' 23:59:59');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Get users with advanced search
     *
     * @param   array   $search_params  Search parameters
     * @return  array   Array of user objects
     */
    public function search_users_advanced($search_params) {
        // Apply different search criteria
        if (isset($search_params['email']) && !empty($search_params['email'])) {
            $this->db->like('email', $search_params['email']);
        }
        
        if (isset($search_params['username']) && !empty($search_params['username'])) {
            $this->db->like('username', $search_params['username']);
        }
        
        if (isset($search_params['name']) && !empty($search_params['name'])) {
            $this->db->group_start();
            $this->db->like('first_name', $search_params['name']);
            $this->db->or_like('last_name', $search_params['name']);
            $this->db->group_end();
        }
        
        if (isset($search_params['phone']) && !empty($search_params['phone'])) {
            $this->db->like('phone', $search_params['phone']);
        }
        
        if (isset($search_params['city']) && !empty($search_params['city'])) {
            $this->db->like('city', $search_params['city']);
        }
        
        if (isset($search_params['country']) && !empty($search_params['country'])) {
            $this->db->like('country', $search_params['country']);
        }
        
        // Date range filters
        if (isset($search_params['created_from']) && !empty($search_params['created_from'])) {
            $this->db->where('created_at >=', $search_params['created_from'] . ' 00:00:00');
        }
        
        if (isset($search_params['created_to']) && !empty($search_params['created_to'])) {
            $this->db->where('created_at <=', $search_params['created_to'] . ' 23:59:59');
        }
        
        // Status filters
        if (isset($search_params['status'])) {
            switch ($search_params['status']) {
                case 'active':
                    $this->db->where('banned', '0');
                    break;
                case 'banned':
                    $this->db->where('banned', '1');
                    break;
                case 'verified':
                    $this->db->where('email_verified', '1');
                    break;
                case 'unverified':
                    $this->db->where('email_verified', '0');
                    break;
                case 'locked':
                    $this->db->where('locked', '1');
                    break;
            }
        }
        
        // Level filter
        if (isset($search_params['level']) && !empty($search_params['level'])) {
            $this->db->where('auth_level', $search_params['level']);
        }
        
        // Sorting
        $sort_by = isset($search_params['sort_by']) ? $search_params['sort_by'] : 'created_at';
        $sort_order = isset($search_params['sort_order']) ? $search_params['sort_order'] : 'DESC';
        
        $allowed_sort_fields = array('user_id', 'username', 'email', 'first_name', 'last_name', 'auth_level', 'banned', 'email_verified', 'created_at', 'last_login');
        if (in_array($sort_by, $allowed_sort_fields)) {
            $this->db->order_by($sort_by, $sort_order);
        }
        
        // Limit results
        $limit = isset($search_params['limit']) ? (int)$search_params['limit'] : 100;
        $this->db->limit($limit);
        
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Approve a pending user (activate account)
     */
    public function approve_user($user_id) {
        $update_data = array(
            'banned' => '0',
            'pending_verification' => '0'
        );
        
        // Add optional fields if they exist
        $columns = $this->db->list_fields('users');
        if (in_array('banned_reason', $columns)) {
            $update_data['banned_reason'] = NULL;
        }
        if (in_array('banned_at', $columns)) {
            $update_data['banned_at'] = NULL;
        }
        if (in_array('banned_by', $columns)) {
            $update_data['banned_by'] = NULL;
        }
        if (in_array('modified_by', $columns)) {
            $update_data['modified_by'] = $this->session->userdata('user_id') ?: 'admin';
        }
        
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $update_data);
    }

}

/* End of file M_users.php */
/* Location: ./application/models/M_users.php */
