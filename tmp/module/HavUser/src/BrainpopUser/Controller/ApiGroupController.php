<?php

namespace BrainpopUser\Controller;

use Application\Controller\AbstractController;

use Zend\View\Model\JsonModel;

class ApiGroupController extends AbstractController
{
    // Load the repository getters method
    use \BrainpopUser\Repository\RepositoryGetterTrait;

    /**
     * Process the subscribe users to group action
     * @return object JsonModel
     */
    public function subscribeUsersAction()
    {
        $postData       = $this->request->getPost()->toArray();
        $digitValidator = new \Zend\Validator\Digits();

        if (
            empty($postData['account_id']) || !$digitValidator->isValid($postData['account_id'])
            || empty($postData['group_id']) || !$digitValidator->isValid($postData['group_id'])
            || empty($postData['users_ids']) || !count($postData['users_ids'])
        ) {
            return new JsonModel(array('success', false));
        }

        $accountUsers = $this->getUserRepository()->ListUsersGroupAssociation($postData['account_id'], $postData['group_id']);
        if (!$accountUsers->count()) {
            return new JsonModel(array('success', false));
        }

        foreach ($postData['users_ids'] as $userId) {
            if ($accountUsers->isSubscribed($userId)) {
                return new JsonModel(array('success', false));
            }
        }

        $this->getGroupRepository()->subscribeUsers($postData['group_id'], $postData['users_ids']);

        return new JsonModel(array(
            'success' => true,
        ));
    }

    /**
     * Process the subscribe users to group action
     * @return object JsonModel
     */
    public function unsubscribeUsersAction()
    {
        $postData       = $this->request->getPost()->toArray();
        $digitValidator = new \Zend\Validator\Digits();

        if (
            empty($postData['account_id']) || !$digitValidator->isValid($postData['account_id'])
            || empty($postData['group_id']) || !$digitValidator->isValid($postData['group_id'])
            || empty($postData['users_ids']) || !count($postData['users_ids'])
        ) {
            return new JsonModel(array('success', false));
        }

        $accountUsers = $this->getUserRepository()->ListUsersGroupAssociation($postData['account_id'], $postData['group_id']);
        if (!$accountUsers->count()) {
            return new JsonModel(array('success', false));
        }

        foreach ($postData['users_ids'] as $userId) {
            if (!$accountUsers->isSubscribed($userId)) {
                return new JsonModel(array('success', false));
            }
        }

        $this->getGroupRepository()->unsubscribeUsers($postData['group_id'], $postData['users_ids']);

        return new JsonModel(array(
            'success' => true,
        ));
    }

} // End of class
