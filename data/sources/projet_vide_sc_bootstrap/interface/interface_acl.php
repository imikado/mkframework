<?php

interface interface_acl {

	public function register();

	public function purge();

	public function allow($action,$ressource);

	public function deny($action,$ressource);

	public function can($action,$ressource);
}
