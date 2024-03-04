# Basic auth with multiple single sign on(SSO) providers
## Development Process
Using Laravel Breeze with Vue.js, I created a basic auth app. By default, Laravel Breeze provides functionalities like user registration, login, logout, and forgot password.
Then, for multiple SSO functionality, I created a table named `user_social_auths` using migration. With consideration that a user can have multiple social accounts.
For SSO, I used the Laravel Socialite package, which supports authentication via many social platforms like Google, GitHub, Facebook, Twitter, etc.
Then, I created a controller named `SocialAuthController` 
which handles redirection and callback for social providers.
## Project currently supports:
- Can add as many social providers as we want.
- Can automatically associate with the user if the social account has the same email ID.
- Can create a new account if the user's email ID does not exist.

## Future enhancements:
- Allow users to link or unlink their social accounts after login.
- Can also store other information from social providers like avatar, access tokens.
- Handle scenarios where a social provider does not provide an email ID.

## Prerequisites
- PHP>=8.1
- Composer
- node and npm
- mySql and any supported database

## setup
- Clone the repository
```bash
git clone https://github.com/your-username/your-repository.git
```
- Install composer dependencies
```bash
composer install
```
- Install npm dependencies
```bash
npm install
```
- Setup environment
```.dotenv
cp .env.example .env 
```
- Run migration
```bash
php artisan migrate
```

# Additional Configuration
- Add SSO Credentials in env
```php
GOOGLE_CLIENT_ID=""
GOOGLE_CLIENT_SECRET=""
GOOGLE_REDIRECT_URI=${APP_URL}/social/login/google/callback
..... 
```
- SSO Credentials inside config/service.php file
 ```php
 'google' => [
'client_id' => env('GOOGLE_CLIENT_ID'),
'client_secret' => env('GOOGLE_CLIENT_SECRET'),
'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```
## Testing Scenarios 
Some test cases like login, registration, logout, etc. are included in Laravel Breeze by default. For Single Sign-On (SSO) integration, the following scenarios I tested:
* SSO Redirection: Validate that users are correctly redirected to the appropriate Single Sign-On (SSO) provider's authentication page.
* SSO Callback: Verify that the application correctly handles callbacks from SSO providers and associates the user's account with the respective social profile.
* SSO Linkage: Test the linkage between user accounts and their associated social profiles to ensure accurate data representation.
```bash
php artisan test
```