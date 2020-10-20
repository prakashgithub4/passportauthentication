# passportauthentication
for facing errors at the time of migrate table

   public function register()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }
    add this under your app/http/providers/AppServiceProvider.php file
