<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2017-02-08 15:37:15                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoUserIdentityDAO extends StorableDAO
	{
		protected $linkName =  'main';
		
		public function getTable()
		{
			return 'user_identity';
		}
		
		public function getObjectName()
		{
			return 'UserIdentity';
		}
		
		public function getSequence()
		{
			return 'user_identity_id';
		}
	}
?>