<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2015-04-18 21:38:25                    *
 *   This file will never be generated again - feel free to edit.            *
 *****************************************************************************/

	final class UserModerationsDAO extends OneToManyLinked
	{
		public function __construct(User $user, $lazy = false)
		{
			parent::__construct(
				$user,
				BoardModerator::dao(),
				$lazy
			);
		}
		
		/**
		 * @return UserModerationsDAO
		**/
		public static function create(User $user, $lazy = false)
		{
			return new self($user, $lazy);
		}
		
		public function getParentIdField()
		{
			return 'user_id';
		}
	}
?>