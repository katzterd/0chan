<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2017-03-29 18:26:37                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoModeratorLog_Property extends ModeratorLog
	{
		protected $propertyName = null;
		protected $oldValue = null;
		protected $newValue = null;
		
		public function getPropertyName()
		{
			return $this->propertyName;
		}
		
		/**
		 * @return ModeratorLog_Property
		**/
		public function setPropertyName($propertyName)
		{
			$this->propertyName = $propertyName;
			
			return $this;
		}
		
		public function getOldValue()
		{
			return $this->oldValue;
		}
		
		/**
		 * @return ModeratorLog_Property
		**/
		public function setOldValue($oldValue)
		{
			$this->oldValue = $oldValue;
			
			return $this;
		}
		
		public function getNewValue()
		{
			return $this->newValue;
		}
		
		/**
		 * @return ModeratorLog_Property
		**/
		public function setNewValue($newValue)
		{
			$this->newValue = $newValue;
			
			return $this;
		}
	}
?>