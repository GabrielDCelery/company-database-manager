var companiesFactory = angular.module('companiesFactory', [])

/***********************************************************************************
Changing which form is displayed dependent on which submenu button the user clicks on
***********************************************************************************/
companiesFactory.factory("menuButtons", function(){

	function changeFormDisplay(propertyName, object, callback){
		for(var key in object){
			if(key == propertyName){
				object[key] = true;
			} else {
				object[key] = false;
			}
		}
		callback(object)
	}

	return {
		changeFormDisplay: changeFormDisplay
	}

})

/***********************************************************************************
Getting company names and filtering them while using the search form
***********************************************************************************/

companiesFactory.factory("getCompanyNames", function($http){

	var cachedData;

	function getData(callback){
		if(cachedData){
			callback(cachedData);
		} else {
			$http.get('src/app-features/companies/fetch_company_names.php').success(function(data){
				cachedData = data;
				callback(data);
			});
		}
	}

	function filterData(inputCompanyName, callback){
		var listOfCompanyNames = [];
		var filteredListOfCompanyNames = [];
		getData(function(data){
			listOfCompanyNames = data;
			if(inputCompanyName.length != 0){
				for(var i=0; i < listOfCompanyNames.length; i++){
					if(listOfCompanyNames[i].company_name.substring(0,inputCompanyName.length).toLowerCase() === inputCompanyName.toLowerCase()){
						filteredListOfCompanyNames.push(listOfCompanyNames[i].company_name);
					}
				}
			}
		});
		callback(filteredListOfCompanyNames);
	}

	return {
		filterList: filterData
	}

})

/***********************************************************************************
Getting manager names and filtering them while using the search form
***********************************************************************************/

companiesFactory.factory("getManagerNames", function($http){

	var cachedData;

	function getData(callback){
		if(cachedData){
			callback(cachedData);
		} else {
			$http.get('src/app-features/companies/fetch_manager_names.php').success(function(data){
				cachedData = data;
				callback(data);
			});
		}
	}

	function filterData(inputManagerName, callback){
		var listOfManagerNames = [];
		var filteredListOfManagerNames = [];
		getData(function(data){
			listOfManagerNames = data;
			if(inputManagerName.length !==0){
				for(var i=0; i < listOfManagerNames.length; i++){
					if(listOfManagerNames[i].manager_name.substring(0,inputManagerName.length).toLowerCase() === inputManagerName.toLowerCase()){
						filteredListOfManagerNames.push(listOfManagerNames[i].manager_name);
					}
				}
			}
		});
		callback(filteredListOfManagerNames);
	}

	return {
		filterList: filterData
	}

})

/***********************************************************************************
Data manipulator functions
***********************************************************************************/

companiesFactory.factory("companyFunctions", function($http, language){

	var data;
	var language

	language.getData(function(data){
		language = data;
	})

	function convertDate(stringDate){
		var outputDate = new Date(stringDate);
		return outputDate;
	}

	function connectToDatabase(method, url, input, callback){
		$http({
			method: method,
			url: url,
			data: input
		}).success(function(data){
			callback(data);
		})
	}

	function addColourCoding(){
		this.data.map(function(obj){
			if (obj.contract_status == true && (obj.postal_number == "" || obj.postal_number == null)){
				obj.css_color = "yellow";
			} else if (obj.contract_status == true){
				obj.css_color = "green";
			} else {
				obj.css_color = "red";
			}
		})
		return this;
	}

	function formatDateCorrectly(){
		this.data.map(function(obj){
			if(obj.starting_date == "1970-01-01" || obj.starting_date == "0000-00-00" || obj.starting_date == null){
				obj.forwarding_date = null;
			} else {
				obj.starting_date = convertDate(obj.starting_date);
			}
			if(obj.ending_date == "1970-01-01" || obj.ending_date == "0000-00-00" || obj.ending_date == null){
				obj.ending_date = null;
			} else {
				obj.ending_date = convertDate(obj.ending_date);
			}
			if(obj.transfer_date == "1970-01-01" || obj.transfer_date == "0000-00-00" || obj.transfer_date == null){
				obj.transfer_date = null;
			} else {
				obj.transfer_date = convertDate(obj.transfer_date);
			}
			if(obj.invoice_date == "1970-01-01" || obj.invoice_date == "0000-00-00" || obj.invoice_date == null){
				obj.invoice_date = null;
			} else {
				obj.invoice_date = convertDate(obj.invoice_date);
			}
		})
		return this;
	}

	function formatPostalServiceToString(){
		this.data.map(function(obj){
			if(obj.postal_service == 1){
				obj.postal_service = language.companies.data.postalservice.yes;
			} else {
				obj.postal_service = language.companies.data.postalservice.no;
			}
		})
		return this;
	}

	return {
		data: data,
		connectToDatabase: connectToDatabase,
		addColourCoding: addColourCoding,
		formatDateCorrectly: formatDateCorrectly,
		formatPostalServiceToString: formatPostalServiceToString
	}

})

/***********************************************************************************
Language converter
***********************************************************************************/

companiesFactory.factory("language", function($http){

	var cachedData;

	function getData(callback){
		if(cachedData){
			callback(cachedData);
		} else {
			$http.get("settings/language.json").success(function(data){
				cachedData = data;
				callback(data);
			})
		}
	}

	function postalServiceConverter(input, languageFile){

		var inputData = angular.copy(input);

		if(inputData.postal_service == languageFile.companies.data.postalservice.yes){
			inputData.postal_service = 1;
		} else {
			inputData.postal_service = 0;
		}
		
		return inputData;
	}

	return {
		getData: getData,
		postalServiceConverter: postalServiceConverter
	}
})


/***********************************************************************************
Contract generator
***********************************************************************************/

companiesFactory.factory("contractDocMaker", function($http){

	function createCover(input, callback){
		$http({
			method: 'POST',
			url: 'src/app-features/companies/document_create_cover.php',
			data: input
		}).success(function(){
			callback();
		})
	}

	function createContract(input, callback){
		$http({
			method: 'POST',
			url: 'src/app-features/companies/document_create_contract.php',
			data: input
		}).success(function(){
			callback();
		})
	}

	return {
		createContract: createContract,
		createCover: createCover
	}

})