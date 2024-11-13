<?php
// AdminController.php
require_once '../lib/BaseController.php';
require_once '../models/Admin.php';
class AdminController extends BaseController {
    private $adminModel;
    
    public function __construct() {
        $this->adminModel = new Admin();
    }
    
    public function createAdmin($data) {
        try {
            $this->validateRequest($data, ['name', 'email', 'username', 'password', 'role']);
            $this->validatePermissions($_SESSION['admin_id'], 'super_admin');
            
            $adminId = $this->adminModel->create($data);
            return $this->respondSuccess(['id' => $adminId], 'Admin created successfully');
        } catch (ValidationException $e) {
            return $this->respondError($e->getMessage(), 400);
        } catch (AuthorizationException $e) {
            return $this->respondError($e->getMessage(), 403);
        } catch (Exception $e) {
            Logger::error("Admin creation error: " . $e->getMessage());
            return $this->respondError('Internal server error', 500);
        }
    }
    
    public function login($username, $password) {
        try {
            $this->validateRequest(['username' => $username, 'password' => $password], 
                                 ['username', 'password']);
            
            $admin = $this->adminModel->login($username, $password);
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_role'] = $admin['role'];
                return $this->respondSuccess($admin, 'Login successful');
            }
            return $this->respondError('Invalid credentials', 401);
        } catch (Exception $e) {
            Logger::error("Admin login error: " . $e->getMessage());
            return $this->respondError('Login failed', 500);
        }
    }

    public function getProfile($adminId) {
        try {
            // Validate the admin ID
            if (!$adminId) {
                return $this->respondError('Admin ID is required', 400);
            }
            
            // Get the admin profile data
            $adminProfile = $this->adminModel->getProfileById($adminId);
            if ($adminProfile) {
                return $this->respondSuccess($adminProfile, 'Profile retrieved successfully');
            }

            return $this->respondError('Admin profile not found', 404);
        } catch (Exception $e) {
            Logger::error("Get profile error: " . $e->getMessage());
            return $this->respondError('Internal server error', 500);
        }
    }

    public function updateProfile($adminId, $updatedData) {
        try {
            // Validate the updated data
            $this->validateRequest($updatedData, ['name', 'email', 'contactNumber']);

            // Validate the admin ID
            if (!$adminId) {
                return $this->respondError('Admin ID is required', 400);
            }

            // Update the admin profile data
            $updateSuccess = $this->adminModel->updateProfileById($adminId, $updatedData);
            if ($updateSuccess) {
                return $this->respondSuccess([], 'Profile updated successfully');
            }

            return $this->respondError('Failed to update profile', 500);
        } catch (ValidationException $e) {
            return $this->respondError($e->getMessage(), 400);
        } catch (Exception $e) {
            Logger::error("Update profile error: " . $e->getMessage());
            return $this->respondError('Internal server error', 500);
        }
    }

}

?>
