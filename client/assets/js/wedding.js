var wedding = angular.module('wedding', ['ngRoute', 'checklist-model']);

wedding.config(function ($routeProvider) {
    $routeProvider
    .when('/', {
        controller:'loginController as login',
        templateUrl:'login.html',
    })      
    .when('/overview', {
        controller: 'overviewController as overview',
        templateUrl:'overview.html'
    })    
     .when('/answer', {
        controller: 'answerController as answer',
        templateUrl:'answer.html'
    })
     .when('/userContact', {
        controller: 'userContactController as userContact',
        templateUrl:'userContact.html'
    })
    .when('/partyMenu', {
        controller: 'partyMenuController as partyMenu',
        templateUrl:'partyMenu.html'
    })
    .when('/intolerances', {
        controller: 'intolerancesController as intolerances',
        templateUrl:'intolerances.html'
    })
    .when('/other', {
        controller: 'otherController as other',
        templateUrl:'other.html'
    })
     .when('/arrival', {
        controller: 'arrivalController as arrival',
        templateUrl:'arrival.html'
    })
    .when('/departure', {        
        templateUrl:'departure.html'
    })
     .when('/sleepover', {
        controller: 'sleepoverController as sleepover',
        templateUrl:'sleepover.html'
    })
     .when('/sheet', {       
        templateUrl:'sheet.html'
    })
    .when('/contact', {
        controller: 'contactController as contact',
        templateUrl:'contact.html'
    })
    .when('/wishlist', {
        controller: 'wishlistController as wishlist',
        templateUrl:'wishlist.html'
    })
    .otherwise({
        redirectTo:'/'
    })
});

wedding.run(function($rootScope, $location, $http){
    $rootScope.navigate = function (pageName) {
        $location.path('/' + pageName);
    }

    $rootScope.$on( "$routeChangeStart", function(event, next, current) {
        if (!$rootScope.UserData || !$rootScope.UserData.UserCodeHash) {       
            if (next.templateUrl != "login.html" ) $location.path( "/login" );        
        }   
        else{                        
            $http({method: 'POST', url: '../../../controller/updateUserData.php', data:$rootScope.UserData})
                .then(function successCallback(response) {
                    $rootScope.UserData = response.data.UserData;           
                });
        }      
    });
})
