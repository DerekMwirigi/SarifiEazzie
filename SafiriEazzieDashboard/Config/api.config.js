baseurl = {
    url: 'http://localhost:81/DevOps/SafiriEazzie/SafiriEazzieApi/api/',
}
endpoints = {
    auth: {
        signIn: baseurl.url + 'oauth/verifyPassword.php',
        signUp: baseurl.url + 'oauth/signup.php',
    },
    driver: {
        create: baseurl.url + 'driver/create.php',
        fetch: baseurl.url + 'driver/fetch.php'
    },
    owner: {
        create: baseurl.url + 'owner/create.php',
        fetch: baseurl.url + 'owner/fetch.php'
    },
    vehicle: {
        create: baseurl.url + 'vehicle/create.php',
        fetch: baseurl.url + 'vehicle/fetch.php'
    },
    common: {
        create_route:  baseurl.url + 'common/create-route.php',
        fetch_routes: baseurl.url + 'common/fetch-routes.php',
        create_vehicle_make:  baseurl.url + 'common/create-vehicle-make.php',
        fetch_vehicle_makes: baseurl.url + 'common/fetch-vehicle-makes.php',
        fetch_vehicle_models: baseurl.url + 'common/fetch-vehicle-models.php',
        fetch_owners: baseurl.url + 'common/fetch-owners-fetch.php'
    },
    analysis: {
        stats: baseurl.url + 'analysis/stats.php'
    }
}