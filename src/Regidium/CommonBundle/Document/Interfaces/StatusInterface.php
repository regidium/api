<?php

namespace Regidium\CommonBundle\Document\Interfaces;

interface StatusInterface
{

	public function getStatus();
	public function setStatus($status);
	static public function getStatuses();

}