
var mailingController = angular.module('mailingController', []);

mailingController.controller('mailingCtrl', function(
	$scope, 
	$http, 
	$window,
	menuButtons,
	getCompanyNames,
	getMailingAddresses,
	mailFunctions,
	receit

){

/***********************************************************************************
Variables and objects
***********************************************************************************/

/* Object to control what's displayed on the screen and what's not */
	$scope.display = {
		menu: {
			searchMails: true,
			forwardMails: false,
			editMails: false,
			addNewMails: true,
			reset: true
		},

		form: {
			searchMails: false,
			forwardMails: false,
			editMails: false,
			addNewMails: false
		},

		data: {
			mailsShortlist: false
		},

		loadingScreen: false
	}

/* Formdata for searching mails */
	$scope.formForSearchingMailData = {
		companyName: "",
		startingDate: null,
		endingDate: null,
		nonForwarded: true,
		forwarded: false,
		hasPostalService: true,
		doesntHavePoastalService: true
	}

/* Formdata for forwarding mails */
	$scope.formForForwardingMails = {
		forwardingDate: null,
		forwardingMethod: null,
		id: null
	}

/* Formdata for adding new mails */
	$scope.formForAddingNewMails = {
		companyName: "",
		receivingDate: null,
		mails: [{
			senderName: "",
			senderAddress: "",
			activeField: false
		}]
	}

/* Object holding the id's of selected mails for editing or forwarding */
	$scope.selectedMails = {
		id: [],
		allChecked: false
	}

/* Master objects */

	$scope.formForSearchingMailDataMaster = angular.copy($scope.formForSearchingMailData);
	$scope.formForForwardingMailsMaster = angular.copy($scope.formForForwardingMails);
	$scope.formForAddingNewMailsMaster = angular.copy($scope.formForAddingNewMails);
	$scope.displayMaster = angular.copy($scope.display);

/***********************************************************************************
Feature menu button functions
***********************************************************************************/

	$scope.searchMailsButton = function(){
		menuButtons.changeFormDisplay("searchMails", $scope.display.form, function(data){
			$scope.display.form = data;
		})
	}

	$scope.forwardMailsButton = function(){
		menuButtons.changeFormDisplay("forwardMails", $scope.display.form, function(data){
			$scope.display.form = data;
		})
	}

	$scope.editMailsButton = function(){
		menuButtons.changeFormDisplay("editMails", $scope.display.form, function(data){
			$scope.display.form = data;
		})
	}

	$scope.addNewMailsButton = function(){
		menuButtons.changeFormDisplay("addNewMails", $scope.display.form, function(data){
			$scope.display.form = data;
		})
	}

	$scope.resetDataButton = function(){
		$scope.confirmReset = confirm($scope.language.mailing.confirm.reset);
		if($scope.confirmReset == true){
			$scope.resetData();
			$scope.resetDisplay();
		}
	}

/***********************************************************************************
FORM - SEARCH / FILTER COMPANY NAMES AND MAILING ADDRESSES
***********************************************************************************/

	/* Make a google-like filtering of the listed company names */
	$scope.filterListOfCompanyNamesSearch = function(){
		getCompanyNames.filterList($scope.formForSearchingMailData.companyName, function(data){
			$scope.filteredListOfCompanyNames = data;
	})};

	$scope.filterListOfCompanyNamesAddNew = function(){
		getCompanyNames.filterList($scope.formForAddingNewMails.companyName, function(data){
			$scope.filteredListOfCompanyNames = data;
	})};

	/* Insert the selected name into the input field while filtering in search menu */
	$scope.insertCompanyNameToSearchField = function(companyName){
		$scope.formForSearchingMailData.companyName = companyName;
		$scope.filteredListOfCompanyNames = [];
	};

	$scope.filterListOfMailingAddresses = function(newMail){
		getMailingAddresses.filterList(newMail.senderName, newMail.senderAddress, function(data){
			$scope.filteredListOfMailingAddresses = data;

			var index = $scope.formForAddingNewMails.mails.indexOf(newMail);
			for(var i = 0; i < $scope.formForAddingNewMails.mails.length; i++){
				$scope.formForAddingNewMails.mails[i].activeField = false;
			}
			$scope.formForAddingNewMails.mails[index].activeField = true;

		})
	}
	
	/* Insert the selected name into the input field while filtering in add new mail */
	$scope.insertCompanyNameToAddNewMailNameField = function(companyName){
		$scope.formForAddingNewMails.companyName = companyName;
		$scope.filteredListOfCompanyNames = [];
	};

	$scope.insertMailingAddressToAddNewMailField = function(mailingAddress){
		for(var i = 0; i < $scope.formForAddingNewMails.mails.length; i++){
			if($scope.formForAddingNewMails.mails[i].activeField == true){
				$scope.formForAddingNewMails.mails[i].senderName = mailingAddress.sender_name;
				$scope.formForAddingNewMails.mails[i].senderAddress = mailingAddress.sender_address;
			}
		}
		$scope.filteredListOfMailingAddresses = [];
	}

/***********************************************************************************
FORM - SEARCH
***********************************************************************************/

	$scope.formSearchMails = function(){
		$scope.display.loadingScreen = true;
		$scope.display.form.searchMails = false;
		$scope.display.menu.forwardMails = true;
		$scope.display.menu.editMails = true;
		$scope.display.data.mailsShortlist = true;

		mailFunctions.connectToDatabase(
			'POST',
			'src/app-features/mailing/form_search_mails.php',
			$scope.formForSearchingMailData,
			function(data){
				mailFunctions.data = data;
				mailFunctions.formatDateCorrectly().addColourCoding();
				$scope.mailsShortList = mailFunctions.data;
				$scope.mailsShortListMaster = angular.copy($scope.mailsShortList);
				$scope.display.loadingScreen = false;
			}
		)

		$scope.selectedMails = {
			id: [],
			allChecked: false
		}
	}

/***********************************************************************************
Edit mails functions
***********************************************************************************/

/* Edit selected mails */
	$scope.editMailData = function(){
		if($scope.selectedMails.id.length == 0){
			alert($scope.language.mailing.alert.checklistempty)
		} else {
			for(var i = 0; i < $scope.mailsShortList.length; i++){
				$scope.mailsShortList[i].show_input = false;
			}
			for(var i = 0; i < $scope.selectedMails.id.length; i++){
				for(var j = 0; j < $scope.mailsShortList.length; j++){
					if($scope.selectedMails.id[i] == $scope.mailsShortList[j].mail_id){
						$scope.mailsShortList[j].show_input = true;
					}
				}
			}
		}
	}

/* Reset listed mails table */
	$scope.resetMailData = function(){
		$scope.mailsShortList = angular.copy($scope.mailsShortListMaster);
	}

/* Overwrite selected mail data */
	$scope.formOverWriteMails = function(){
		var confirmOverwrite = confirm($scope.language.mailing.confirm.overwritedata);
		if(confirmOverwrite == true){
			$scope.display.loadingScreen = true;
			mailFunctions.connectToDatabase(
				'POST',
				'src/app-features/mailing/form_overwrite_mails.php',
				$scope.mailsShortList,
				function(data){
					$scope.formSearchMails();
					$scope.display.loadingScreen = false;
					alert($scope.language.mailing.alert.successfuloverwrite);
				}
			)
		}
	}

/* Delete slected mails */
	$scope.formDeleteMails = function(){
		if($scope.selectedMails.id.length == 0){
			alert($scope.language.mailing.alert.checklistempty)
		} else {
			$scope.confirmDelete = confirm($scope.language.mailing.confirm.deletemails);
			if($scope.confirmDelete == true){
				$scope.display.loadingScreen = true;
				mailFunctions.connectToDatabase(
					'POST',
					'src/app-features/mailing/form_delete_mails.php',
					$scope.selectedMails,
					function(data){
						$scope.formSearchMails();
						$scope.display.loadingScreen = false;
						alert($scope.language.mailing.alert.successfuloverwrite);
					}
				)
			}
		}
	}

/***********************************************************************************
Forward mails functions
***********************************************************************************/

	$scope.formForwardMails = function(){
		if($scope.selectedMails.id.length > 0){
			if(($scope.formForForwardingMails.forwardingDate != null && $scope.formForForwardingMails.forwardingMethod == null) || ($scope.formForForwardingMails.forwardingDate == null && $scope.formForForwardingMails.forwardingMethod != null)){
				alert($scope.language.mailing.alert.bothfilledorempty)
			} else {
				$scope.confirmForwarding = confirm($scope.language.mailing.confirm.forwardmails);
				if($scope.confirmForwarding == true){
					$scope.display.loadingScreen = true;
					$scope.formForForwardingMails.id = $scope.selectedMails.id;
					mailFunctions.connectToDatabase(
						'POST',
						'src/app-features/mailing/form_forward_mails.php',
						$scope.formForForwardingMails,
						function(data){
							$scope.formSearchMails();
							$scope.display.loadingScreen = false;
							alert($scope.language.mailing.alert.successfuloverwrite);
						}
					)
				}
			}
		} else {
			alert($scope.language.mailing.alert.checklistempty);
		}
	}

	$scope.printReceit = function(){
		$scope.display.loadingScreen = true;
		var filteredData = receit.filterList($scope.selectedMails.id, $scope.mailsShortList);
		receit.createReceit(filteredData, function(){
			window.location.replace("src/app-features/mailing/receit.docx");
			$scope.display.loadingScreen = false;
		})

	}

/***********************************************************************************
Adding new mails functions
***********************************************************************************/
	$scope.addNewRowToAddMailForm = function(){
		$scope.formForAddingNewMails.mails.push({
			senderName: "",
			senderAddress: ""
		});
	}

	$scope.removeRowFromAddMailForm = function(data){
		var index = $scope.formForAddingNewMails.mails.indexOf(data);
		if(index != -1 && $scope.formForAddingNewMails.mails.length > 1){
			$scope.formForAddingNewMails.mails.splice(index, 1);
		}
	}

	$scope.formAddNewMails = function(){
		var alertToFillData = false;
		if($scope.formForAddingNewMails.companyName == null){
			alertToFillData = true;
		}
		if($scope.formForAddingNewMails.receivingDate == null){
			alertToFillData = true;
		}
		for(var i = 0; i < $scope.formForAddingNewMails.mails.length; i++){
			if($scope.formForAddingNewMails.mails[i].senderName.length == 0 || $scope.formForAddingNewMails.mails[i].senderAddress.length == 0){
				alertToFillData = true;
			}
		}
		if(alertToFillData == true){
			alert($scope.language.mailing.alert.allhavetobefilled)
		} else {
			$scope.display.loadingScreen = true;
			mailFunctions.connectToDatabase(
				'POST',
				'src/app-features/mailing/form_add_new_mails.php',
				$scope.formForAddingNewMails,
				function(data){
					$scope.formForSearchingMailData = {
						companyName: $scope.formForAddingNewMails.companyName,
						startingDate: null,
						endingDate: null,
						nonForwarded: true,
						forwarded: false,
						hasPostalService: true,
						doesntHavePoastalService: true
					}

					$scope.formSearchMails();
					$scope.display.loadingScreen = false;
					alert($scope.language.mailing.alert.emailsaddedsuccessfully);
				}
			)
		}
	}

/***********************************************************************************
Checklist model
***********************************************************************************/

	$scope.checkAll = function (){
		if ($scope.selectedMails.allChecked == false){
			angular.forEach($scope.mailsShortList, function(item){
				$scope.selectedMails.id.push(item.mail_id)
			})
			$scope.selectedMails.allChecked = true;
		} else {
			$scope.selectedMails.id = [];
			$scope.selectedMails.allChecked = false;
		}
	}

/***********************************************************************************
Reset data and display functions
***********************************************************************************/

	$scope.resetData = function(){
		$scope.formForSearchingMailData = angular.copy($scope.formForSearchingMailDataMaster);
		$scope.formForForwardingMails = angular.copy($scope.formForForwardingMailsMaster);
		$scope.formForAddingNewMails = angular.copy($scope.formForAddingNewMailsMaster);

		$scope.filteredListOfCompanyNames = [];
		$scope.filteredListOfMailingAddresses = [];
		$scope.mailsShortList = {};
		$scope.mailsShortListMaster = {};
	}

	$scope.resetDisplay = function(){
		$scope.display = angular.copy($scope.displayMaster);
	}



}) //End of controller