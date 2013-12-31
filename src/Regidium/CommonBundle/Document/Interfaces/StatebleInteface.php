<?php

namespace Regidium\CommonBundle\Document\Interfaces;

interface StatebleInteface
{

	public function getState();
	public function setState($state);
	static public function getStates();

}