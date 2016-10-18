wedding.directive('navigations', ['$rootScope', function ($rootScope) {
    return {
        scope:{
            back:"=",
            forward:"=",
            disabledBack:"=",
            disabledForward:"=",
            hideBack:"=",
            hideForward:"="
        },
        templateUrl : "assets/js/directives/navigations.html"
    };
}]);