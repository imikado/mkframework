<?php

interface VARinterfaceModelRightmanagerENDVAR {

	public function findById($uId);

	public function findAll();

	public function findListByGroup($group_id);

	public function insertGroup($sName);

	public function insertAction($sName);

	public function insertItem($sName);

	public function findGroupByName($sName);

	public function findActionByName($sName);

	public function findItemByName($sName);

	public function findSelectGroup();

	public function findSelectAction();

	public function findSelectItem();

	public function findListUser();

	public function findUserById($user_id);

	public function updateUserGroup($user_id,$group_id);

	public function update($oItem);

}
