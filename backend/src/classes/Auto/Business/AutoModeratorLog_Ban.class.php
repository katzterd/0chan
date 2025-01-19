<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2017-03-29 18:26:37                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoModeratorLog_Ban extends ModeratorLog
	{
		protected $ban = null;
		protected $banId = null;
		
		/**
		 * @return Ban
		**/
		public function getBan()
		{
			if (!$this->ban && $this->banId) {
				$this->ban = Ban::dao()->getById($this->banId);
			}
			
			return $this->ban;
		}
		
		public function getBanId()
		{
			return $this->ban
				? $this->ban->getId()
				: $this->banId;
		}
		
		/**
		 * @return ModeratorLog_Ban
		**/
		public function setBan(Ban $ban = null)
		{
			$this->ban = $ban;
			$this->banId = $ban ? $ban->getId() : null;
			
			return $this;
		}
		
		/**
		 * @return ModeratorLog_Ban
		**/
		public function setBanId($id = null)
		{
			$this->ban = null;
			$this->banId = $id;
			
			return $this;
		}
		
		/**
		 * @return ModeratorLog_Ban
		**/
		public function dropBan()
		{
			$this->ban = null;
			$this->banId = null;
			
			return $this;
		}
	}
?>