<?php
namespace hav\V1\Rest\OnlineUsers;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class OnlineUsersResource extends AbstractResourceListener
{
    private function getUsers()
    {
        return unserialize(file_get_contents('onlineUsers.bin'));
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $users = unserialize(file_get_contents('onlineUsers.bin'));
        $newuser["id"] = $data->id;
        $newuser["username"] = $data->username;
        $newuser["nick"] = $data->nick;
        $newuser["email"] = $data->email;
        $users[$data->id] = $newuser;
        file_put_contents('onlineUsers.bin', serialize($users));
//        return new ApiProblem(405, 'The POST method has not been defined');
        return $newuser;
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $users = unserialize(file_get_contents('onlineUsers.bin'));
        unset($users[$id]);
        file_put_contents('onlineUsers.bin', serialize($users));
        return true;
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $users = unserialize(file_get_contents('onlineUsers.bin'));
        // could be replaced to inarray but it's gone change anyway.
        foreach($users as $user){
            if($user['id']==$id){
                return $user;
            }
        }
        return new ApiProblem(400, 'User not found');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        $online = $this->getUsers();
        file_put_contents('onlineUsers.bin', serialize($online));
        return($online);
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
