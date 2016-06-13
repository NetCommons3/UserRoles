/**
 * @fileoverview UserRoles Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */


/**
 * UserRoles controller
 */
NetCommonsApp.controller('UserRolesController',
    ['$scope', 'NetCommonsModal', function($scope, NetCommonsModal) {

      /**
       * 会員権限詳細表示
       *
       * @param {number} users.id
       * @return {void}
       */
      $scope.showUserRole = function(key) {
        NetCommonsModal.show(
            $scope, 'UserRolesView',
            $scope.baseUrl + '/user_roles/user_roles/view/' + key + '',
            {windowClass: 'user-role-modal'}
        );
      };
    }]);


/**
 * UserRoles modal controller
 */
NetCommonsApp.controller('UserRolesView',
    ['$scope', '$uibModalInstance', function($scope, $uibModalInstance) {

      /**
       * dialog cancel
       *
       * @return {void}
       */
      $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel');
      };
    }]);
