wedding.controller('answerController', ['$http', '$location', '$rootScope',function ($http, $location, $rootScope) {
    var me = this;
    me.UpdateAnswer = function (isComing) {
        $rootScope.UserData.IsComing = isComing;              
    }
}]);