<?php
namespace BrainpopUser;

class Module
{
    
    /**
     * Load the autoload maps
     * @return array 
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Load the config from the configuration file
     * @return array 
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(

                'bp_user_model' => function ($sm) {
                    $model = new \BrainpopUser\Model\User($sm->get('bp_user_mapper_model'));
                    $model->setAuth($sm->get('auth_service'));
                    return $model;
                },
                'bp_user_mapper_model' => function ($sm) {
                    return new \BrainpopUser\Model\UserMapper($sm->get('db_adapter'));
                },

            ),
            'invokables' => array(
                'login_form_filter'     => 'HavUser\Form\LoginFilter',
                'login_form'            => 'HavUser\Form\LoginForm',
            )
        );
    }

}
