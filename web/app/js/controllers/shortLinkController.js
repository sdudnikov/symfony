/**
 * Created by Сергей on 17.02.2017.
 */

var myApp = angular.module('myApp');
myApp.controller('shortLinkController', function($scope, $http, $httpParamSerializerJQLike) {

    $scope.url = {};
    $scope.listOfUrl = {};
    $scope.modalMsg = "";

    $http.get('/links').then(
        function success(response) {
            $scope.listOfUrl = response.data;
            var grid = angular.element(document.querySelector('#grid'));
            grid.removeClass('hide');
        },
        function error(response) {
            var modal = angular.element(document.querySelector('#modal'));
            $scope.modalMsg = "Unexpected error! Can not load urls!";
            modal.css('display', 'block');
        }
    );


    $scope.generateUrl = function(sendForm){
        if(sendForm.$valid){
            var modal = angular.element(document.querySelector('#modal'));
            $http({
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                url:'/generate',
                data: $httpParamSerializerJQLike({original: $scope.url.original, alias: $scope.url.alias})
            }).then(
                function success(response) {
                    $scope.modalMsg = response.data.msg;
                    if(response.data.status == 'success'){
                        $scope.listOfUrl.push({origin_url: $scope.url.original,
                            alias_url: window.location.origin + '/' + $scope.url.alias});
                    }
                    modal.css('display', 'block');
                },
                function error(response) {
                    $scope.modalMsg = "Unexpected error! Can not generate url!";
                    modal.css('display', 'block');
                }
            );
        }
    };

    $scope.hideModal = function(){
        var modal = angular.element(document.querySelector('#modal'));
        modal.css('display', 'none');
    };

    $scope.copy = function(url){
        copyTextToClipboard(url.alias_url);
    };

    function copyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }

});
