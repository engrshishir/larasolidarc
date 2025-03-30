<?php
declare(strict_types=1);

namespace App\Permissions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\User;

/**
 * Abstract class for managing permission checks in the application.
 * 
 * This class provides common functionality for checking permissions and handling
 * unauthorized access. It is intended to be extended by specific permission
 * classes (e.g., UserPermission, RolePermission) to implement detailed error messages
 * and permission checking logic for specific resources.
 * 
 * @package App\Permissions
 */
abstract class PermissionChecker
{
    /**
     * Abstract method to retrieve error messages for a given permission key.
     * 
     * This method should be implemented in subclasses to provide specific error
     * messages for each permission, depending on the action being performed.
     * 
     * @param string $permissionKey The permission key (e.g., "users.create", "roles.view").
     * @return string The error message to be displayed if permission is not granted.
     */
    abstract protected function getErrorMessages(string $permissionKey): string;








    /**
     * Checks if the user is authorized to perform the action and throws an exception if not.
     * 
     * This method is typically used after performing a permission check to ensure that
     * the user has the required permissions. If not, an HTTP exception will be thrown.
     * 
     * @param bool $isAuthorized Whether the user is authorized to perform the action.
     * @param string|null $authorizationMessage A custom authorization message (optional).
     * 
     * @throws HttpException If the user is not authorized, a 401 Unauthorized HTTP exception is thrown.
     */
    public function checkAuthResponse(bool $isAuthorized, ?string $authorizationMessage = null): void
    {
        // If the user is not authorized, throw an Unauthorized exception.
        if (!$isAuthorized) {
            throw new HttpException(
                Response::HTTP_UNAUTHORIZED,
                empty($authorizationMessage) ? $this->getDefaultAuthorizationErrorMessage() : $authorizationMessage,
            );
        }
    }






    /**
     * Returns the default authorization error message when no custom message is provided.
     * 
     * @return string The default error message for unauthorized actions.
     */
    protected function getDefaultAuthorizationErrorMessage(): string
    {
        return __('You are not authorized to do this action.');
    }






    /**
     * Checks if the authenticated user has the necessary permissions.
     * 
     * This method checks if the current authenticated user has one or more permissions
     * required to perform an action.
     * 
     * @param mixed $permissions A single permission or an array of permissions to check.
     * 
     * @return bool True if the user has the required permissions, false otherwise.
     */
    protected function hasPermissions(mixed $permissions): bool
    {
        // Ensure that permissions are in an array format.
        $permissions = (array) is_array($permissions) ? $permissions : [$permissions];

        // Check if the authenticated user has any of the required permissions.
        return $this->getAuthUser()->hasAnyPermission($permissions);
    }








    /**
     * Retrieves the authenticated user.
     * 
     * This method returns the currently authenticated user, throwing an exception if
     * the user is not logged in.
     * 
     * @return User The authenticated user.
     * 
     * @throws HttpException If no user is authenticated, a 401 Unauthorized exception is thrown.
     */
    protected function getAuthUser(): User
    {
        // Get the authenticated user from the request.
        $authenticatedUser = request()->user();

        // If no user is authenticated, throw an Unauthorized exception.
        if (!$authenticatedUser) {
            throw new HttpException(
                Response::HTTP_UNAUTHORIZED,
                __('Please be authenticated to do this action.'),
            );
        }

        return $authenticatedUser;
    }







    

    /**
     * Throws an exception indicating that the user is unauthorized to perform the action.
     * 
     * This method is used to throw a "Forbidden" exception (HTTP 403) when the user does
     * not have the necessary permissions to perform an action.
     * 
     * @param string $message The message to include in the exception.
     * 
     * @throws HttpException Throws a 403 Forbidden exception with the provided message.
     */
    protected function throwUnAuthorizedException(string $message)
    {
        throw new HttpException(Response::HTTP_FORBIDDEN, $message);
    }
}
