<?php

interface interface_model {

	public function findById($id);

	public function insert($oItem);

	public function update($oItem);

	public function delete($oItem);
}
