wedding.controller('intolerancesController', ['$rootScope',function ($rootScope) {
    var me = this;

    me.FoodTypes = ["Melk", "Kjøtt", "Gluten"];
    me.AllergensList = [];
    me.AllergensString = "";
    
    me.UpdateAllergens = function () {
        var selectedAllergens =  me.AllergensList.toString();
        $rootScope.UserData.Allergens = selectedAllergens + ((selectedAllergens && me.AllergensString) ? ',' : '') + me.AllergensString;              
    }

    me.SetAllergens = function() {
        var allergens = $rootScope.UserData.Allergens.split(',');
        for (var i = 0; i < allergens.length; i++) {
            var element = allergens[i];
            if(me.FoodTypes.indexOf(element) > -1) me.AllergensList.push(element);
            else me.AllergensString += element;
        }
    }

    me.SetAllergens();
    
}]);