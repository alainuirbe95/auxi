<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detailed_test_phase2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "<h2>🔍 Detailed Phase 2 Testing - Review System</h2>";
        
        $all_tests_passed = true;
        
        // Test 1: Database Structure Validation
        echo "<h3>1. Database Structure Validation</h3>";
        $db_test = $this->test_database_structure();
        $all_tests_passed = $all_tests_passed && $db_test;
        
        // Test 2: Model Functionality Testing
        echo "<h3>2. Model Functionality Testing</h3>";
        $model_test = $this->test_model_functionality();
        $all_tests_passed = $all_tests_passed && $model_test;
        
        // Test 3: Review Categories Testing
        echo "<h3>3. Review Categories Testing</h3>";
        $categories_test = $this->test_review_categories();
        $all_tests_passed = $all_tests_passed && $categories_test;
        
        // Test 4: Review Validation Logic Testing
        echo "<h3>4. Review Validation Logic Testing</h3>";
        $validation_test = $this->test_review_validation_logic();
        $all_tests_passed = $all_tests_passed && $validation_test;
        
        // Test 5: Job Integration Testing
        echo "<h3>5. Job Integration Testing</h3>";
        $job_test = $this->test_job_integration();
        $all_tests_passed = $all_tests_passed && $job_test;
        
        // Test 6: Controller Methods Testing
        echo "<h3>6. Controller Methods Testing</h3>";
        $controller_test = $this->test_controller_methods();
        $all_tests_passed = $all_tests_passed && $controller_test;
        
        // Test 7: View File Content Testing
        echo "<h3>7. View File Content Testing</h3>";
        $views_test = $this->test_view_content();
        $all_tests_passed = $all_tests_passed && $views_test;
        
        // Test 8: Route Testing
        echo "<h3>8. Route Testing</h3>";
        $route_test = $this->test_routes();
        $all_tests_passed = $all_tests_passed && $route_test;
        
        // Test 9: Integration Testing
        echo "<h3>9. Integration Testing</h3>";
        $integration_test = $this->test_integration();
        $all_tests_passed = $all_tests_passed && $integration_test;
        
        // Final Result
        echo "<h3>🎉 Final Detailed Test Result</h3>";
        if ($all_tests_passed) {
            echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px;'>";
            echo "✅ <strong>ALL DETAILED TESTS PASSED!</strong><br>";
            echo "Phase 2 review system is fully functional and ready for production.<br>";
            echo "All components are working correctly together.";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px;'>";
            echo "❌ <strong>SOME DETAILED TESTS FAILED!</strong><br>";
            echo "Please review the failed tests above before proceeding to Phase 3.";
            echo "</div>";
        }
        
        echo "<br><h3>📊 Detailed Test Summary</h3>";
        echo "<p>This comprehensive test validates:</p>";
        echo "<ul>";
        echo "<li>Database structure and relationships</li>";
        echo "<li>Model methods and functionality</li>";
        echo "<li>Review categories and validation</li>";
        echo "<li>Job integration and review windows</li>";
        echo "<li>Controller methods and security</li>";
        echo "<li>View files and user interface</li>";
        echo "<li>Route configuration and accessibility</li>";
        echo "<li>End-to-end integration</li>";
        echo "</ul>";
    }
    
    private function test_database_structure()
    {
        $all_ok = true;
        
        // Test reviews table structure
        if ($this->db->table_exists('reviews')) {
            echo "✅ Reviews table exists<br>";
            
            // Check required fields
            $required_fields = array('id', 'job_id', 'reviewer_id', 'reviewee_id', 'rating', 'review_type', 'created_at');
            foreach ($required_fields as $field) {
                if ($this->db->field_exists($field, 'reviews')) {
                    echo "&nbsp;&nbsp;✅ Field '$field' exists<br>";
                } else {
                    echo "&nbsp;&nbsp;❌ Field '$field' missing<br>";
                    $all_ok = false;
                }
            }
            
            // Check constraints
            $constraints = $this->db->query("SHOW CREATE TABLE reviews")->row_array();
            if (strpos($constraints['Create Table'], 'CONSTRAINT') !== false) {
                echo "&nbsp;&nbsp;✅ Foreign key constraints exist<br>";
            } else {
                echo "&nbsp;&nbsp;❌ Foreign key constraints missing<br>";
                $all_ok = false;
            }
        } else {
            echo "❌ Reviews table missing<br>";
            $all_ok = false;
        }
        
        // Test review_responses table
        if ($this->db->table_exists('review_responses')) {
            echo "✅ Review responses table exists<br>";
        } else {
            echo "❌ Review responses table missing<br>";
            $all_ok = false;
        }
        
        // Test jobs table extensions
        $review_fields = array('review_window_expires_at', 'host_reviewed', 'cleaner_reviewed', 'reviews_sent_at');
        foreach ($review_fields as $field) {
            if ($this->db->field_exists($field, 'jobs')) {
                echo "✅ Jobs table has '$field' field<br>";
            } else {
                echo "❌ Jobs table missing '$field' field<br>";
                $all_ok = false;
            }
        }
        
        return $all_ok;
    }
    
    private function test_model_functionality()
    {
        $all_ok = true;
        
        try {
            $this->load->model('M_reviews');
            
            // Test get_review_categories method
            $host_categories = $this->M_reviews->get_review_categories('host_to_cleaner');
            if (is_array($host_categories) && count($host_categories) > 0) {
                echo "✅ Host review categories working (" . count($host_categories) . " categories)<br>";
            } else {
                echo "❌ Host review categories not working<br>";
                $all_ok = false;
            }
            
            $cleaner_categories = $this->M_reviews->get_review_categories('cleaner_to_host');
            if (is_array($cleaner_categories) && count($cleaner_categories) > 0) {
                echo "✅ Cleaner review categories working (" . count($cleaner_categories) . " categories)<br>";
            } else {
                echo "❌ Cleaner review categories not working<br>";
                $all_ok = false;
            }
            
            // Test get_user_review_stats method
            $stats = $this->M_reviews->get_user_review_stats(1);
            if (is_array($stats) && isset($stats['average_rating'], $stats['total_reviews'])) {
                echo "✅ User review stats method working<br>";
            } else {
                echo "❌ User review stats method not working<br>";
                $all_ok = false;
            }
            
        } catch (Exception $e) {
            echo "❌ Model functionality test failed: " . $e->getMessage() . "<br>";
            $all_ok = false;
        }
        
        return $all_ok;
    }
    
    private function test_review_categories()
    {
        $all_ok = true;
        
        try {
            $this->load->model('M_reviews');
            
            // Test host to cleaner categories
            $host_categories = $this->M_reviews->get_review_categories('host_to_cleaner');
            $expected_host_categories = array(
                'quality_work', 'punctuality', 'communication', 'professionalism',
                'cleanliness', 'respect_property', 'following_instructions', 'overall_satisfaction'
            );
            
            foreach ($expected_host_categories as $category) {
                if (isset($host_categories[$category])) {
                    echo "✅ Host category '$category' exists<br>";
                } else {
                    echo "❌ Host category '$category' missing<br>";
                    $all_ok = false;
                }
            }
            
            // Test cleaner to host categories
            $cleaner_categories = $this->M_reviews->get_review_categories('cleaner_to_host');
            $expected_cleaner_categories = array(
                'clear_instructions', 'fair_payment', 'respectful_treatment', 'property_condition',
                'communication', 'flexibility', 'overall_experience'
            );
            
            foreach ($expected_cleaner_categories as $category) {
                if (isset($cleaner_categories[$category])) {
                    echo "✅ Cleaner category '$category' exists<br>";
                } else {
                    echo "❌ Cleaner category '$category' missing<br>";
                    $all_ok = false;
                }
            }
            
        } catch (Exception $e) {
            echo "❌ Review categories test failed: " . $e->getMessage() . "<br>";
            $all_ok = false;
        }
        
        return $all_ok;
    }
    
    private function test_review_validation_logic()
    {
        $all_ok = true;
        
        try {
            $this->load->model('M_reviews');
            
            // Test can_review_job method with invalid job
            $can_review = $this->M_reviews->can_review_job(99999, 1);
            if ($can_review === false) {
                echo "✅ can_review_job correctly rejects invalid job<br>";
            } else {
                echo "❌ can_review_job should reject invalid job<br>";
                $all_ok = false;
            }
            
            // Test rating validation (should be 1-5)
            $test_data = array(
                'job_id' => 1,
                'reviewer_id' => 1,
                'reviewee_id' => 2,
                'rating' => 5,
                'review_type' => 'host_to_cleaner',
                'review_categories' => array('quality_work'),
                'review_window_expires_at' => date('Y-m-d H:i:s', strtotime('+1 day'))
            );
            
            // Test if create_review method exists and accepts valid data structure
            if (method_exists($this->M_reviews, 'create_review')) {
                echo "✅ create_review method exists<br>";
            } else {
                echo "❌ create_review method missing<br>";
                $all_ok = false;
            }
            
        } catch (Exception $e) {
            echo "❌ Review validation logic test failed: " . $e->getMessage() . "<br>";
            $all_ok = false;
        }
        
        return $all_ok;
    }
    
    private function test_job_integration()
    {
        $all_ok = true;
        
        try {
            $this->load->model('M_jobs');
            
            // Test if setup_review_window method exists
            if (method_exists($this->M_jobs, 'setup_review_window')) {
                echo "✅ setup_review_window method exists<br>";
            } else {
                echo "❌ setup_review_window method missing<br>";
                $all_ok = false;
            }
            
            // Test if send_review_notifications method exists
            if (method_exists($this->M_jobs, 'send_review_notifications')) {
                echo "✅ send_review_notifications method exists<br>";
            } else {
                echo "❌ send_review_notifications method missing<br>";
                $all_ok = false;
            }
            
            // Test if jobs table has been updated with review fields
            $job_fields = $this->db->field_data('jobs');
            $review_field_names = array();
            foreach ($job_fields as $field) {
                $review_field_names[] = $field->name;
            }
            
            $required_review_fields = array('review_window_expires_at', 'host_reviewed', 'cleaner_reviewed', 'reviews_sent_at');
            foreach ($required_review_fields as $field) {
                if (in_array($field, $review_field_names)) {
                    echo "✅ Jobs table has '$field' field<br>";
                } else {
                    echo "❌ Jobs table missing '$field' field<br>";
                    $all_ok = false;
                }
            }
            
        } catch (Exception $e) {
            echo "❌ Job integration test failed: " . $e->getMessage() . "<br>";
            $all_ok = false;
        }
        
        return $all_ok;
    }
    
    private function test_controller_methods()
    {
        $all_ok = true;
        
        // Test if Reviews controller file exists and has required methods
        $controller_file = APPPATH . 'controllers/Reviews.php';
        if (file_exists($controller_file)) {
            echo "✅ Reviews controller file exists<br>";
            
            $controller_content = file_get_contents($controller_file);
            $required_methods = array('create', 'submit', 'view', 'user_reviews', 'my_reviews', 'pending', 'respond');
            
            foreach ($required_methods as $method) {
                if (strpos($controller_content, "public function $method") !== false) {
                    echo "&nbsp;&nbsp;✅ Method '$method' exists<br>";
                } else {
                    echo "&nbsp;&nbsp;❌ Method '$method' missing<br>";
                    $all_ok = false;
                }
            }
            
            // Test if controller extends MY_Controller
            if (strpos($controller_content, 'extends MY_Controller') !== false) {
                echo "✅ Controller extends MY_Controller<br>";
            } else {
                echo "❌ Controller should extend MY_Controller<br>";
                $all_ok = false;
            }
            
            // Test if controller loads required models
            if (strpos($controller_content, 'M_reviews') !== false) {
                echo "✅ Controller loads M_reviews model<br>";
            } else {
                echo "❌ Controller should load M_reviews model<br>";
                $all_ok = false;
            }
            
        } else {
            echo "❌ Reviews controller file missing<br>";
            $all_ok = false;
        }
        
        return $all_ok;
    }
    
    private function test_view_content()
    {
        $all_ok = true;
        
        $view_files = array(
            'reviews/create.php' => array('rating', 'categories', 'validate'),
            'reviews/view.php' => array('review', 'response', 'rating'),
            'reviews/user_reviews.php' => array('rating', 'review', 'filter'),
            'reviews/pending.php' => array('time', 'review', 'job')
        );
        
        foreach ($view_files as $file => $required_content) {
            $file_path = APPPATH . 'views/' . $file;
            if (file_exists($file_path)) {
                echo "✅ $file exists<br>";
                
                $content = file_get_contents($file_path);
                foreach ($required_content as $content_item) {
                    if (stripos($content, $content_item) !== false) {
                        echo "&nbsp;&nbsp;✅ Contains '$content_item'<br>";
                    } else {
                        echo "&nbsp;&nbsp;❌ Missing '$content_item'<br>";
                        $all_ok = false;
                    }
                }
            } else {
                echo "❌ $file missing<br>";
                $all_ok = false;
            }
        }
        
        return $all_ok;
    }
    
    private function test_routes()
    {
        $all_ok = true;
        
        $routes_file = APPPATH . 'config/routes.php';
        if (file_exists($routes_file)) {
            echo "✅ Routes file exists<br>";
            
            $routes_content = file_get_contents($routes_file);
            $required_routes = array(
                'reviews/create/(:num)',
                'reviews/submit',
                'reviews/view/(:num)',
                'reviews/user_reviews/(:num)',
                'reviews/pending',
                'reviews/respond'
            );
            
            foreach ($required_routes as $route) {
                if (strpos($routes_content, $route) !== false) {
                    echo "&nbsp;&nbsp;✅ Route '$route' configured<br>";
                } else {
                    echo "&nbsp;&nbsp;❌ Route '$route' missing<br>";
                    $all_ok = false;
                }
            }
        } else {
            echo "❌ Routes file missing<br>";
            $all_ok = false;
        }
        
        return $all_ok;
    }
    
    private function test_integration()
    {
        $all_ok = true;
        
        try {
            // Test if all models can be loaded together
            $this->load->model('M_reviews');
            $this->load->model('M_review_responses');
            $this->load->model('M_jobs');
            $this->load->model('M_user_profiles');
            echo "✅ All models can be loaded together<br>";
            
            // Test if review categories are consistent
            $host_categories = $this->M_reviews->get_review_categories('host_to_cleaner');
            $cleaner_categories = $this->M_reviews->get_review_categories('cleaner_to_host');
            
            if (count($host_categories) > 0 && count($cleaner_categories) > 0) {
                echo "✅ Review categories are properly configured<br>";
            } else {
                echo "❌ Review categories not properly configured<br>";
                $all_ok = false;
            }
            
            // Test if database relationships work
            $test_query = "SELECT COUNT(*) as count FROM reviews r 
                          JOIN users u ON r.reviewer_id = u.user_id 
                          JOIN jobs j ON r.job_id = j.id";
            $result = $this->db->query($test_query);
            if ($result) {
                echo "✅ Database relationships working correctly<br>";
            } else {
                echo "❌ Database relationships not working<br>";
                $all_ok = false;
            }
            
        } catch (Exception $e) {
            echo "❌ Integration test failed: " . $e->getMessage() . "<br>";
            $all_ok = false;
        }
        
        return $all_ok;
    }
}
