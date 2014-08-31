(function () {
	var app = angular.module('hexavigesimal', []);
	app.controller('HexavigesimalController', [function () {
		this.convert = function (decimal) {
		    var converted = '';
		    // Repeatedly divide the number by 26 and convert the
		    // remainder into the appropriate letter.
		    while (decimal > 0)
		    {
		        var remainder = (decimal - 1) % 26;
		        converted = String.fromCharCode(remainder + 97) + converted;
		        decimal = Math.floor((decimal - remainder) / 26);
		    }

		    return converted;
		};
	}]);
})();