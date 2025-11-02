<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Profiles Model
 * 
 * Handles all database operations related to user profiles
 */
class M_user_profiles extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get user profile by user ID
     */
    public function get_profile_by_user_id($user_id)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $this->db->select('up.*, u.username, u.email, u.first_name, u.last_name');
        $this->db->from('user_profiles up');
        $this->db->join('users u', 'up.user_id = u.user_id');
        $this->db->where('up.user_id', $user_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Get public profile by user ID
     */
    public function get_public_profile($user_id)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $this->db->select('up.*, u.username, u.first_name, u.last_name');
        $this->db->from('user_profiles up');
        $this->db->join('users u', 'up.user_id = u.user_id');
        $this->db->where('up.user_id', $user_id);
        $this->db->where('up.is_public', 1);
        
        return $this->db->get()->row();
    }
    
    /**
     * Create or update user profile
     */
    public function save_profile($user_id, $profile_data)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        // Check if profile exists
        $existing_profile = $this->get_profile_by_user_id($user_id);
        
        $data = array(
            'user_id' => $user_id,
            'bio' => $profile_data['bio'] ?? null,
            'phone' => $profile_data['phone'] ?? null,
            'service_areas' => isset($profile_data['service_areas']) ? json_encode($profile_data['service_areas']) : null,
            'profile_picture_url' => $profile_data['profile_picture_url'] ?? null,
            'cover_photo_url' => $profile_data['cover_photo_url'] ?? null,
            'specialties' => isset($profile_data['specialties']) ? json_encode($profile_data['specialties']) : null,
            'availability_schedule' => isset($profile_data['availability_schedule']) ? json_encode($profile_data['availability_schedule']) : null,
            'is_public' => $profile_data['is_public'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if ($existing_profile) {
            // Update existing profile
            $this->db->where('user_id', $user_id);
            $result = $this->db->update('user_profiles', $data);
        } else {
            // Create new profile
            $data['created_at'] = date('Y-m-d H:i:s');
            $result = $this->db->insert('user_profiles', $data);
        }
        
        return $result;
    }
    
    /**
     * Update profile (flexible - only updates provided fields)
     */
    public function update_profile($user_id, $update_data)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        // Check if profile exists
        $existing_profile = $this->get_profile_by_user_id($user_id);
        
        if (!$existing_profile) {
            // If profile doesn't exist, create it first
            $this->create_default_profile($user_id);
        }
        
        // Only update fields that are provided
        $allowed_fields = ['bio', 'phone', 'service_areas', 'specialties', 'services_str', 'profile_picture_url', 
                          'cover_photo_url', 'availability_schedule', 'is_public', 'has_supplies', 'updated_at'];
        
        $data = [];
        foreach ($update_data as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                $data[$key] = $value;
            }
        }
        
        // Always update the timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        if (empty($data)) {
            return false;
        }
        
        // Update profile
        $this->db->where('user_id', $user_id);
        return $this->db->update('user_profiles', $data);
    }
    
    /**
     * Update profile statistics
     */
    public function update_profile_stats($user_id, $stats_data)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $data = array(
            'response_time_avg' => $stats_data['response_time_avg'] ?? null,
            'completion_rate' => $stats_data['completion_rate'] ?? null,
            'total_jobs_completed' => $stats_data['total_jobs_completed'] ?? null,
            'average_rating' => $stats_data['average_rating'] ?? null,
            'total_reviews' => $stats_data['total_reviews'] ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('user_id', $user_id);
        return $this->db->update('user_profiles', $data);
    }
    
    /**
     * Get profile statistics
     */
    public function get_profile_stats($user_id)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return array(
                'average_rating' => 0,
                'total_reviews' => 0,
                'total_jobs_completed' => 0,
                'completion_rate' => 0,
                'response_time_avg' => 0
            );
        }
        
        $profile = $this->get_profile_by_user_id($user_id);
        
        if (!$profile) {
            return array(
                'average_rating' => 0,
                'total_reviews' => 0,
                'total_jobs_completed' => 0,
                'completion_rate' => 0,
                'response_time_avg' => 0
            );
        }
        
        return array(
            'average_rating' => (float)($profile->average_rating ?? 0),
            'total_reviews' => (int)($profile->total_reviews ?? 0),
            'total_jobs_completed' => (int)($profile->total_jobs_completed ?? 0),
            'completion_rate' => (float)($profile->completion_rate ?? 0),
            'response_time_avg' => (int)($profile->response_time_avg ?? 0)
        );
    }
    
    /**
     * Search profiles by criteria
     */
    public function search_profiles($criteria = array(), $limit = 20, $offset = 0)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return array();
        }
        
        $this->db->select('up.*, u.username, u.first_name, u.last_name');
        $this->db->from('user_profiles up');
        $this->db->join('users u', 'up.user_id = u.user_id');
        $this->db->where('up.is_public', 1);
        
        // Apply search criteria
        if (!empty($criteria['service_areas'])) {
            $this->db->like('up.service_areas', $criteria['service_areas']);
        }
        
        if (!empty($criteria['specialties'])) {
            $this->db->like('up.specialties', $criteria['specialties']);
        }
        
        if (!empty($criteria['min_rating'])) {
            $this->db->where('up.average_rating >=', $criteria['min_rating']);
        }
        
        if (!empty($criteria['verification_status'])) {
            $this->db->where('up.verification_status', $criteria['verification_status']);
        }
        
        $this->db->order_by('up.average_rating', 'DESC');
        $this->db->order_by('up.total_reviews', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get top rated profiles
     */
    public function get_top_rated_profiles($limit = 10)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return array();
        }
        
        $this->db->select('up.*, u.username, u.first_name, u.last_name');
        $this->db->from('user_profiles up');
        $this->db->join('users u', 'up.user_id = u.user_id');
        $this->db->where('up.is_public', 1);
        $this->db->where('up.average_rating >', 0);
        $this->db->where('up.total_reviews >=', 3); // At least 3 reviews
        $this->db->order_by('up.average_rating', 'DESC');
        $this->db->order_by('up.total_reviews', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get all profiles (Admin only - no public filter)
     */
    public function get_all_profiles($filters = array(), $limit = 20, $offset = 0)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return array('profiles' => array(), 'total' => 0);
        }
        
        $this->db->select('up.*, u.username, u.email, u.first_name, u.last_name, u.auth_level, u.banned, u.created_at as user_created_at');
        $this->db->from('user_profiles up');
        $this->db->join('users u', 'up.user_id = u.user_id', 'left');
        
        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('u.username', $search);
            $this->db->or_like('u.first_name', $search);
            $this->db->or_like('u.last_name', $search);
            $this->db->or_like('u.email', $search);
            $this->db->or_like('up.bio', $search);
            $this->db->group_end();
        }
        
        if (!empty($filters['role'])) {
            // 3 = Cleaner, 6 = Host, 9 = Admin
            $this->db->where('u.auth_level', $filters['role']);
        }
        
        if (!empty($filters['verification_status'])) {
            $this->db->where('up.verification_status', $filters['verification_status']);
        }
        
        if (isset($filters['is_public'])) {
            $this->db->where('up.is_public', $filters['is_public']);
        }
        
        // Get total count before applying limit
        $total = $this->db->count_all_results('', false);
        
        // Apply sorting
        $sort_by = $filters['sort_by'] ?? 'created_at';
        $sort_order = $filters['sort_order'] ?? 'DESC';
        $this->db->order_by('up.' . $sort_by, $sort_order);
        
        // Apply pagination
        $this->db->limit($limit, $offset);
        
        $profiles = $this->db->get()->result();
        
        return array(
            'profiles' => $profiles,
            'total' => $total
        );
    }
    
    /**
     * Get profile with full user data (including auth_level)
     */
    public function get_profile_with_user_data($user_id)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $this->db->select('up.*, u.username, u.email, u.first_name, u.last_name, u.auth_level, u.banned, u.phone as user_phone, u.address as user_address, u.city as user_city, u.country as user_country, u.date_of_birth, u.email_verified, u.locked, u.notes, u.login_count, u.last_login, u.created_at as user_created_at, u.modified_at as user_modified_at');
        $this->db->from('user_profiles up');
        $this->db->join('users u', 'up.user_id = u.user_id', 'left');
        $this->db->where('up.user_id', $user_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Create default profile for new user
     */
    public function create_default_profile($user_id)
    {
        if (!$this->db->table_exists('user_profiles')) {
            log_message('error', 'user_profiles table does not exist');
            return false;
        }
        
        // Check if profile already exists
        $existing = $this->get_profile_by_user_id($user_id);
        if ($existing) {
            log_message('info', 'Profile already exists for user_id: ' . $user_id);
            return true; // Profile already exists
        }
        
        $data = array(
            'user_id' => $user_id,
            'bio' => null,
            'phone' => null,
            'service_areas' => null,
            'profile_picture_url' => null,
            'cover_photo_url' => null,
            'verification_status' => 'unverified',
            'verification_documents' => null,
            'specialties' => null,
            'availability_schedule' => null,
            'response_time_avg' => null,
            'completion_rate' => 100.00,
            'total_jobs_completed' => 0,
            'average_rating' => null,
            'total_reviews' => 0,
            'is_public' => 1,
            'has_supplies' => '0', // Required field - default to no supplies
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        log_message('debug', 'Creating profile for user_id: ' . $user_id . ' with data: ' . json_encode($data));
        
        $result = $this->db->insert('user_profiles', $data);
        
        if ($this->db->error()['code'] != 0) {
            log_message('error', 'Profile creation failed for user_id: ' . $user_id . ' - Error: ' . $this->db->error()['message']);
        } else {
            log_message('info', 'Profile created successfully for user_id: ' . $user_id);
        }
        
        return $result;
    }
    
    /**
     * Calculate profile completion percentage
     */
    public function calculate_profile_completion($user_id)
    {
        $profile = $this->get_profile_with_user_data($user_id);
        
        if (!$profile) {
            return array(
                'percentage' => 0,
                'missing' => array('Profile not found'),
                'can_proceed' => false
            );
        }
        
        $user_role = $profile->auth_level; // 3=Cleaner, 6=Host, 9=Admin
        $score = 0;
        $missing = array();
        
        if ($user_role == 3) { // CLEANER
            // Basic info (20%) - auto from registration
            if (!empty($profile->first_name) && !empty($profile->last_name) && !empty($profile->email)) {
                $score += 20;
            } else {
                $missing[] = 'Basic information';
            }
            
            // Bio (15%)
            if (!empty($profile->bio) && strlen($profile->bio) >= 50) {
                $score += 15;
            } else {
                $missing[] = 'Bio (minimum 50 characters)';
            }
            
            // Phone (10%)
            if (!empty($profile->phone) || !empty($profile->user_phone)) {
                $score += 10;
            } else {
                $missing[] = 'Phone number';
            }
            
            // Profile picture (20%)
            if (!empty($profile->profile_picture_url)) {
                $score += 20;
            } else {
                $missing[] = 'Profile picture';
            }
            
            // Service areas (20%)
            if (!empty($profile->service_areas)) {
                $areas = json_decode($profile->service_areas, true);
                if (is_array($areas) && count($areas) > 0) {
                    $score += 20;
                } else {
                    $missing[] = 'Service areas';
                }
            } else {
                $missing[] = 'Service areas';
            }
            
            // Specialties (15%)
            if (!empty($profile->specialties)) {
                $specialties = json_decode($profile->specialties, true);
                if (is_array($specialties) && count($specialties) > 0) {
                    $score += 15;
                } else {
                    $missing[] = 'Specialties';
                }
            } else {
                $missing[] = 'Specialties';
            }
            
        } else if ($user_role == 6) { // HOST
            // Basic info (25%)
            if (!empty($profile->first_name) && !empty($profile->last_name) && !empty($profile->email)) {
                $score += 25;
            } else {
                $missing[] = 'Basic information';
            }
            
            // Bio (15%)
            if (!empty($profile->bio) && strlen($profile->bio) >= 30) {
                $score += 15;
            } else {
                $missing[] = 'Bio (minimum 30 characters)';
            }
            
            // Phone (15%)
            if (!empty($profile->phone) || !empty($profile->user_phone)) {
                $score += 15;
            } else {
                $missing[] = 'Phone number';
            }
            
            // Profile picture (20%)
            if (!empty($profile->profile_picture_url)) {
                $score += 20;
            } else {
                $missing[] = 'Profile picture';
            }
            
            // Address/Location (25%)
            if (!empty($profile->user_address) || !empty($profile->user_city)) {
                $score += 25;
            } else {
                $missing[] = 'Address or city';
            }
        } else {
            // Admin - always 100%
            $score = 100;
        }
        
        return array(
            'percentage' => $score,
            'missing' => $missing,
            'can_proceed' => ($score >= 50)
        );
    }
    
    /**
     * Get count of profiles pending verification
     */
    public function get_verification_pending_count()
    {
        if (!$this->db->table_exists('user_profiles')) {
            return 0;
        }
        
        $this->db->where('verification_status', 'pending');
        return $this->db->count_all_results('user_profiles');
    }
    
    /**
     * Update verification status
     */
    public function update_verification_status($user_id, $status, $admin_notes = null)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $data = array(
            'verification_status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('user_id', $user_id);
        return $this->db->update('user_profiles', $data);
    }
    
    /**
     * Check if user has profile
     */
    public function has_profile($user_id)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $this->db->where('user_id', $user_id);
        $count = $this->db->count_all_results('user_profiles');
        
        return $count > 0;
    }
    
    /**
     * Get available service areas for cleaners (Mexican States and Cities)
     */
    public function get_service_areas()
    {
        return [
            // Format: 'City, State'
            
            // ===== AGUASCALIENTES =====
            'Aguascalientes, Aguascalientes',
            'Calvillo, Aguascalientes',
            'Jesús María, Aguascalientes',
            'Pabellón de Arteaga, Aguascalientes',
            'Rincón de Romos, Aguascalientes',
            
            // ===== BAJA CALIFORNIA =====
            'Tijuana, Baja California',
            'Mexicali, Baja California',
            'Ensenada, Baja California',
            'Rosarito, Baja California',
            'Tecate, Baja California',
            'San Felipe, Baja California',
            'Valle de Guadalupe, Baja California',
            
            // ===== BAJA CALIFORNIA SUR =====
            'La Paz, Baja California Sur',
            'Cabo San Lucas, Baja California Sur',
            'San José del Cabo, Baja California Sur',
            'Todos Santos, Baja California Sur',
            'Loreto, Baja California Sur',
            'Mulegé, Baja California Sur',
            
            // ===== CAMPECHE =====
            'Campeche, Campeche',
            'Ciudad del Carmen, Campeche',
            'Champotón, Campeche',
            'Escárcega, Campeche',
            
            // ===== CHIAPAS =====
            'Tuxtla Gutiérrez, Chiapas',
            'San Cristóbal de las Casas, Chiapas',
            'Tapachula, Chiapas',
            'Comitán, Chiapas',
            'Palenque, Chiapas',
            'Ocosingo, Chiapas',
            
            // ===== CHIHUAHUA =====
            'Chihuahua, Chihuahua',
            'Ciudad Juárez, Chihuahua',
            'Cuauhtémoc, Chihuahua',
            'Delicias, Chihuahua',
            'Hidalgo del Parral, Chihuahua',
            'Nuevo Casas Grandes, Chihuahua',
            
            // ===== COAHUILA =====
            'Saltillo, Coahuila',
            'Torreón, Coahuila',
            'Monclova, Coahuila',
            'Piedras Negras, Coahuila',
            'Ciudad Acuña, Coahuila',
            'Ramos Arizpe, Coahuila',
            
            // ===== COLIMA =====
            'Colima, Colima',
            'Manzanillo, Colima',
            'Tecomán, Colima',
            'Villa de Álvarez, Colima',
            
            // ===== DURANGO =====
            'Durango, Durango',
            'Gómez Palacio, Durango',
            'Lerdo, Durango',
            'Ciudad Lerdo, Durango',
            'Santiago Papasquiaro, Durango',
            
            // ===== GUANAJUATO =====
            'León, Guanajuato',
            'Irapuato, Guanajuato',
            'Celaya, Guanajuato',
            'Salamanca, Guanajuato',
            'Guanajuato, Guanajuato',
            'San Miguel de Allende, Guanajuato',
            'Silao, Guanajuato',
            'Pénjamo, Guanajuato',
            
            // ===== GUERRERO =====
            'Acapulco, Guerrero',
            'Chilpancingo, Guerrero',
            'Iguala, Guerrero',
            'Zihuatanejo, Guerrero',
            'Taxco, Guerrero',
            'Ixtapa, Guerrero',
            
            // ===== HIDALGO =====
            'Pachuca, Hidalgo',
            'Tulancingo, Hidalgo',
            'Tula de Allende, Hidalgo',
            'Tizayuca, Hidalgo',
            'Tepeji del Río, Hidalgo',
            
            // ===== JALISCO =====
            'Guadalajara, Jalisco',
            'Zapopan, Jalisco',
            'Tlaquepaque, Jalisco',
            'Tonalá, Jalisco',
            'Puerto Vallarta, Jalisco',
            'Lagos de Moreno, Jalisco',
            'Tepatitlán, Jalisco',
            'Chapala, Jalisco',
            'Ajijic, Jalisco',
            
            // ===== MÉXICO =====
            'Toluca, México',
            'Ecatepec, México',
            'Naucalpan, México',
            'Tlalnepantla, México',
            'Nezahualcóyotl, México',
            'Cuautitlán Izcalli, México',
            'Metepec, México',
            'Valle de Chalco, México',
            
            // ===== MICHOACÁN =====
            'Morelia, Michoacán',
            'Uruapan, Michoacán',
            'Zamora, Michoacán',
            'Lázaro Cárdenas, Michoacán',
            'Pátzcuaro, Michoacán',
            'Zitácuaro, Michoacán',
            
            // ===== MORELOS =====
            'Cuernavaca, Morelos',
            'Jiutepec, Morelos',
            'Cuautla, Morelos',
            'Temixco, Morelos',
            'Yautepec, Morelos',
            
            // ===== NAYARIT =====
            'Tepic, Nayarit',
            'Bahía de Banderas, Nayarit',
            'Nuevo Vallarta, Nayarit',
            'San Blas, Nayarit',
            'Compostela, Nayarit',
            
            // ===== NUEVO LEÓN =====
            'Monterrey, Nuevo León',
            'San Pedro Garza García, Nuevo León',
            'Guadalupe, Nuevo León',
            'San Nicolás de los Garza, Nuevo León',
            'Apodaca, Nuevo León',
            'Santa Catarina, Nuevo León',
            'Escobedo, Nuevo León',
            
            // ===== OAXACA =====
            'Oaxaca de Juárez, Oaxaca',
            'Salina Cruz, Oaxaca',
            'Puerto Escondido, Oaxaca',
            'Huatulco, Oaxaca',
            'Juchitán, Oaxaca',
            
            // ===== PUEBLA =====
            'Puebla, Puebla',
            'Tehuacán, Puebla',
            'San Martín Texmelucan, Puebla',
            'Atlixco, Puebla',
            'Cholula, Puebla',
            
            // ===== QUERÉTARO =====
            'Querétaro, Querétaro',
            'San Juan del Río, Querétaro',
            'Corregidora, Querétaro',
            'El Marqués, Querétaro',
            
            // ===== QUINTANA ROO =====
            'Cancún, Quintana Roo',
            'Playa del Carmen, Quintana Roo',
            'Tulum, Quintana Roo',
            'Chetumal, Quintana Roo',
            'Cozumel, Quintana Roo',
            'Isla Mujeres, Quintana Roo',
            
            // ===== SAN LUIS POTOSÍ =====
            'San Luis Potosí, San Luis Potosí',
            'Soledad de Graciano Sánchez, San Luis Potosí',
            'Ciudad Valles, San Luis Potosí',
            'Matehuala, San Luis Potosí',
            
            // ===== SINALOA =====
            'Culiacán, Sinaloa',
            'Mazatlán, Sinaloa',
            'Los Mochis, Sinaloa',
            'Guasave, Sinaloa',
            'Guamúchil, Sinaloa',
            
            // ===== SONORA =====
            'Hermosillo, Sonora',
            'Ciudad Obregón, Sonora',
            'Nogales, Sonora',
            'San Carlos, Sonora',
            'Guaymas, Sonora',
            'Navojoa, Sonora',
            'Puerto Peñasco, Sonora',
            'Caborca, Sonora',
            'Agua Prieta, Sonora',
            
            // ===== TABASCO =====
            'Villahermosa, Tabasco',
            'Cárdenas, Tabasco',
            'Comalcalco, Tabasco',
            'Paraíso, Tabasco',
            
            // ===== TAMAULIPAS =====
            'Reynosa, Tamaulipas',
            'Matamoros, Tamaulipas',
            'Nuevo Laredo, Tamaulipas',
            'Tampico, Tamaulipas',
            'Ciudad Victoria, Tamaulipas',
            'Ciudad Madero, Tamaulipas',
            
            // ===== TLAXCALA =====
            'Tlaxcala, Tlaxcala',
            'Apizaco, Tlaxcala',
            'Huamantla, Tlaxcala',
            
            // ===== VERACRUZ =====
            'Veracruz, Veracruz',
            'Xalapa, Veracruz',
            'Coatzacoalcos, Veracruz',
            'Poza Rica, Veracruz',
            'Córdoba, Veracruz',
            'Orizaba, Veracruz',
            'Boca del Río, Veracruz',
            
            // ===== YUCATÁN =====
            'Mérida, Yucatán',
            'Progreso, Yucatán',
            'Valladolid, Yucatán',
            'Tizimín, Yucatán',
            
            // ===== ZACATECAS =====
            'Zacatecas, Zacatecas',
            'Fresnillo, Zacatecas',
            'Guadalupe, Zacatecas',
            'Jerez, Zacatecas',
            
            // ===== CIUDAD DE MÉXICO =====
            'Benito Juárez, Ciudad de México',
            'Miguel Hidalgo, Ciudad de México',
            'Cuauhtémoc, Ciudad de México',
            'Coyoacán, Ciudad de México',
            'Tlalpan, Ciudad de México',
            'Álvaro Obregón, Ciudad de México',
            'Gustavo A. Madero, Ciudad de México',
            'Iztapalapa, Ciudad de México',
            'Venustiano Carranza, Ciudad de México',
            'Xochimilco, Ciudad de México',
        ];
    }
    
    /**
     * Check if cleaner services a specific location
     * @param int $cleaner_id
     * @param string $job_city
     * @param string $job_state
     * @return bool
     */
    public function cleaner_services_location($cleaner_id, $job_city, $job_state)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $this->db->select('service_areas');
        $this->db->from('user_profiles');
        $this->db->where('user_id', $cleaner_id);
        $query = $this->db->get();
        
        if ($query->num_rows() == 0) {
            return false;
        }
        
        $profile = $query->row();
        $service_areas = json_decode($profile->service_areas, true);
        
        if (!$service_areas || !is_array($service_areas)) {
            return false;
        }
        
        // Create the location string to match
        $job_location = trim($job_city) . ', ' . trim($job_state);
        
        // Check if the job location matches any of the cleaner's service areas
        foreach ($service_areas as $service_area) {
            if (trim($service_area) === $job_location) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get all locations that a cleaner services
     * @param int $cleaner_id
     * @return array
     */
    public function get_cleaner_service_locations($cleaner_id)
    {
        if (!$this->db->table_exists('user_profiles')) {
            return [];
        }
        
        // Use a fresh query to avoid conflicts with active query builder
        $query = $this->db->query(
            "SELECT service_areas FROM user_profiles WHERE user_id = ?",
            [$cleaner_id]
        );
        
        if ($query->num_rows() == 0) {
            return [];
        }
        
        $profile = $query->row();
        $service_areas = json_decode($profile->service_areas, true);
        
        return $service_areas ?: [];
    }
    
    /**
     * Get available cleaning specialties
     */
    public function get_specialties()
    {
        return [
            'General House Cleaning',
            'Deep Cleaning',
            'Move-in/Move-out Cleaning',
            'Post-Construction Cleaning',
            'Office Cleaning',
            'Window Cleaning',
            'Carpet Cleaning',
            'Upholstery Cleaning',
            'Kitchen Deep Clean',
            'Bathroom Sanitization',
            'Floor Polishing',
            'Appliance Cleaning',
            'Laundry Services',
            'Ironing Services',
            'Organization Services',
            'Green/Eco-Friendly Cleaning',
            'Pet Hair Removal',
            'Allergen Reduction',
            'Disinfecting Services',
            'One-time Cleaning',
            'Recurring Cleaning',
            'Holiday Cleaning',
            'Event Preparation',
            'Airbnb Turnover',
            'Commercial Cleaning'
        ];
    }
}
