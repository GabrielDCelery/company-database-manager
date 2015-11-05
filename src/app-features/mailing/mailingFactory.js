var mailingFactory = angular.module('mailingFactory', []);

/***********************************************************************************
Changing which form is displayed dependent on which submenu button the user clicks on
***********************************************************************************/

mailingFactory.factory("menuButtons", function(){

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

mailingFactory.factory("getCompanyNames", function($http){

	var cachedData;

	function getData(callback){
		if(cachedData){
			callback(cachedData);
		} else {
			$http.get('src/app-features/mailing/fetch_company_names.php').success(function(data){
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
Getting mailing addresses and filtering them while using the search form
***********************************************************************************/

mailingFactory.factory("getMailingAddresses", function($http){

	var cachedData;

	function getData(callback){
		if(cachedData){
			callback(cachedData);
		} else {
			$http.get('src/app-features/mailing/fetch_mailing_addresses.php').success(function(data){
				cachedData = data;
				callback(data);
			});
		}
	}

	function filterData(inputSenderName, inputSenderAddress, callback){
		var listOfMailingAddresses = [];
		var filteredListOfMailingAddresses = [];
		getData(function(data){
			listOfMailingAddresses = data;
			if(inputSenderName.length !==0 || inputSenderAddress.length !==0){
				for(var i=0; i < listOfMailingAddresses.length; i++){
					if(listOfMailingAddresses[i].sender_name.substring(0,inputSenderName.length).toLowerCase() === inputSenderName.toLowerCase() && listOfMailingAddresses[i].sender_address.substring(0,inputSenderAddress.length).toLowerCase() === inputSenderAddress.toLowerCase()){
						filteredListOfMailingAddresses.push(listOfMailingAddresses[i]);
					}
				}
			}
		});
		callback(filteredListOfMailingAddresses);
	}

	return {
		filterList: filterData
	}

})

/***********************************************************************************
Data manipulator functions
***********************************************************************************/

mailingFactory.factory("mailFunctions", function($http){

	var data;

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
			obj.show_input = false;
			if (obj.forwarding_date == null){
				obj.css_color = "yellow";
			} else {
				obj.css_color = "green";
			}
		})
		return this;
	}

	function formatDateCorrectly(){
		this.data.map(function(obj){
			obj.receiving_date = convertDate(obj.receiving_date);
			if(obj.forwarding_date == "1970-01-01" || obj.forwarding_date == "0000-00-00" || obj.forwarding_date == null){
				obj.forwarding_date = null;
			} else {
				obj.forwarding_date = convertDate(obj.forwarding_date);
			}
		})
		return this;
	}

	return {
		data: data,
		connectToDatabase: connectToDatabase,
		addColourCoding: addColourCoding,
		formatDateCorrectly: formatDateCorrectly
	}

})


/***********************************************************************************
Receit generator
***********************************************************************************/

mailingFactory.factory("receit", function($http){

	function filterList(arrayId, arrayObjects){

		var filteredData = [];
		
		for(var i = 0; i < arrayId.length; i++){
			for(var j = 0; j < arrayObjects.length; j++){
				if(arrayId[i] == arrayObjects[j]["mail_id"]){
					filteredData.push(arrayObjects[j]);
				}
			}
		}

		return(filteredData);
	}

	function createReceit(input, callback){
		$http({
			method: 'POST',
			url: 'src/app-features/mailing/document_create_receit.php',
			data: input
		}).success(function(){
			callback();
		})
	}

	return {
		filterList: filterList,
		createReceit: createReceit
	}

})