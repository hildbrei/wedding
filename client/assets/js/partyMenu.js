wedding.controller('partyMenuController', ['$rootScope',function ($rootScope) {
    var me = this;

    me.Appetizer1 = "Hummersuppe";
    me.Appetizer2 = "Pesto";

    me.MainCourse1 = "Oksestek";
    me.MainCourse2 = "Ørret";

    me.Dessert1 = "Sjokoladefondant";
    me.Dessert2 = "Iskrem med jordbær";

    me.UpdateAppetizer = function (appetizer) {
        $rootScope.UserData.AppetizerSelected = appetizer;              
    }

     me.UpdateMainCourse = function (course) {
        $rootScope.UserData.MainCourseSelected = course;              
    }

     me.UpdateDessert = function (dessert) {
        $rootScope.UserData.DessertSelected = dessert;              
    }
}]);