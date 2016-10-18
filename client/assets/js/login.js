wedding.controller('loginController', ['$http', '$rootScope', '$location', function ($http, $rootScope, $location) {
    var me = this;
    me.UserCode = "";

    me.Login = function(){
        var postdata = {"UserCode":me.UserCode};
        $http({method: 'POST', url: '../../../controller/login.php', data:postdata}).then(function successCallback(result) {
            if(result.data.Result == "success"){
                $http.defaults.headers.common.Authorization = result.data.UserData.UserCodeHash; 
                $rootScope.UserData = result.data.UserData || {};
                $location.path('/overview');
            }
           else {
               me.LoginError = true;
               me.ErrorMessages = result.data.Messages;
            }

        },function errorCallback(response) {
            me.LoginError = true;            
        })
    }
}]);