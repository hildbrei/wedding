wedding.controller('sleepoverController', ['$rootScope',function ($rootScope) {
    var me = this;   
    me.TFL = "TFL";
    me.FL = "FL";
    me.L = "L";
    me.other = "other";

    me.SelectSleepCabin = function (sleep) {
        $rootScope.UserData.SleepSelected = sleep;
        me.otherSelected = false;
    }

    me.SelectSleepOtherPlace = function(){
        $rootScope.UserData.SleepSelected = me.other;
        me.otherSelected = true;
    }
}]);