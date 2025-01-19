<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2021-08-13 22:29:03                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoUser extends IdentifiableObject
	{
		protected $createDate = null;
		protected $login = null;
		protected $password = null;
		protected $role = null;
		protected $roleId = null;
		protected $deleted = false;
		protected $showNsfw = false;
		protected $treeView = false;
		protected $viewDeleted = false;
		protected $customCss = null;
		protected $favouriteBoards = null;
		protected $posts = null;
		protected $bans = null;
		protected $identities = null;
		protected $invites = null;
		protected $watchedThreads = null;
		
		/**
		 * @return Timestamp
		**/
		public function getCreateDate()
		{
			return $this->createDate;
		}
		
		/**
		 * @return User
		**/
		public function setCreateDate(Timestamp $createDate)
		{
			$this->createDate = $createDate;
			
			return $this;
		}
		
		/**
		 * @return User
		**/
		public function dropCreateDate()
		{
			$this->createDate = null;
			
			return $this;
		}
		
		public function getLogin()
		{
			return $this->login;
		}
		
		/**
		 * @return User
		**/
		public function setLogin($login)
		{
			$this->login = $login;
			
			return $this;
		}
		
		public function getPassword()
		{
			return $this->password;
		}
		
		/**
		 * @return User
		**/
		public function setPassword($password)
		{
			$this->password = $password;
			
			return $this;
		}
		
		/**
		 * @return UserRole
		**/
		public function getRole()
		{
			if (!$this->role && $this->roleId) {
				$this->role = new UserRole($this->roleId);
			}
			
			return $this->role;
		}
		
		public function getRoleId()
		{
			return $this->role
				? $this->role->getId()
				: $this->roleId;
		}
		
		/**
		 * @return User
		**/
		public function setRole(UserRole $role)
		{
			$this->role = $role;
			$this->roleId = $role ? $role->getId() : null;
			
			return $this;
		}
		
		/**
		 * @return User
		**/
		public function setRoleId($id)
		{
			$this->role = null;
			$this->roleId = $id;
			
			return $this;
		}
		
		/**
		 * @return User
		**/
		public function dropRole()
		{
			$this->role = null;
			$this->roleId = null;
			
			return $this;
		}
		
		public function getDeleted()
		{
			return $this->deleted;
		}
		
		public function isDeleted()
		{
			return $this->deleted;
		}
		
		/**
		 * @return User
		**/
		public function setDeleted($deleted = false)
		{
			$this->deleted = ($deleted === true);
			
			return $this;
		}
		
		public function getShowNsfw()
		{
			return $this->showNsfw;
		}
		
		public function isShowNsfw()
		{
			return $this->showNsfw;
		}
		
		/**
		 * @return User
		**/
		public function setShowNsfw($showNsfw = false)
		{
			$this->showNsfw = ($showNsfw === true);
			
			return $this;
		}
		
		public function getTreeView()
		{
			return $this->treeView;
		}
		
		public function isTreeView()
		{
			return $this->treeView;
		}
		
		/**
		 * @return User
		**/
		public function setTreeView($treeView = false)
		{
			$this->treeView = ($treeView === true);
			
			return $this;
		}
		
		public function getViewDeleted()
		{
			return $this->viewDeleted;
		}
		
		public function isViewDeleted()
		{
			return $this->viewDeleted;
		}
		
		/**
		 * @return User
		**/
		public function setViewDeleted($viewDeleted = false)
		{
			$this->viewDeleted = ($viewDeleted === true);
			
			return $this;
		}
		
		public function getCustomCss()
		{
			return $this->customCss;
		}
		
		/**
		 * @return User
		**/
		public function setCustomCss($customCss)
		{
			$this->customCss = $customCss;
			
			return $this;
		}
		
		/**
		 * @return UserFavouriteBoardsDAO
		**/
		public function getFavouriteBoards($lazy = false)
		{
			if (!$this->favouriteBoards || ($this->favouriteBoards->isLazy() != $lazy)) {
				$this->favouriteBoards = new UserFavouriteBoardsDAO($this, $lazy);
			}
			
			return $this->favouriteBoards;
		}
		
		/**
		 * @return User
		**/
		public function fillFavouriteBoards($collection, $lazy = false)
		{
			$this->favouriteBoards = new UserFavouriteBoardsDAO($this, $lazy);
			
			if (!$this->id) {
				throw new WrongStateException(
					'i do not know which object i belong to'
				);
			}
			
			$this->favouriteBoards->mergeList($collection);
			
			return $this;
		}
		
		/**
		 * @return UserPostsDAO
		**/
		public function getPosts($lazy = false)
		{
			if (!$this->posts || ($this->posts->isLazy() != $lazy)) {
				$this->posts = new UserPostsDAO($this, $lazy);
			}
			
			return $this->posts;
		}
		
		/**
		 * @return User
		**/
		public function fillPosts($collection, $lazy = false)
		{
			$this->posts = new UserPostsDAO($this, $lazy);
			
			if (!$this->id) {
				throw new WrongStateException(
					'i do not know which object i belong to'
				);
			}
			
			$this->posts->mergeList($collection);
			
			return $this;
		}
		
		/**
		 * @return UserBansDAO
		**/
		public function getBans($lazy = false)
		{
			if (!$this->bans || ($this->bans->isLazy() != $lazy)) {
				$this->bans = new UserBansDAO($this, $lazy);
			}
			
			return $this->bans;
		}
		
		/**
		 * @return User
		**/
		public function fillBans($collection, $lazy = false)
		{
			$this->bans = new UserBansDAO($this, $lazy);
			
			if (!$this->id) {
				throw new WrongStateException(
					'i do not know which object i belong to'
				);
			}
			
			$this->bans->mergeList($collection);
			
			return $this;
		}
		
		/**
		 * @return UserIdentitiesDAO
		**/
		public function getIdentities($lazy = false)
		{
			if (!$this->identities || ($this->identities->isLazy() != $lazy)) {
				$this->identities = new UserIdentitiesDAO($this, $lazy);
			}
			
			return $this->identities;
		}
		
		/**
		 * @return User
		**/
		public function fillIdentities($collection, $lazy = false)
		{
			$this->identities = new UserIdentitiesDAO($this, $lazy);
			
			if (!$this->id) {
				throw new WrongStateException(
					'i do not know which object i belong to'
				);
			}
			
			$this->identities->mergeList($collection);
			
			return $this;
		}
		
		/**
		 * @return UserInvitesDAO
		**/
		public function getInvites($lazy = false)
		{
			if (!$this->invites || ($this->invites->isLazy() != $lazy)) {
				$this->invites = new UserInvitesDAO($this, $lazy);
			}
			
			return $this->invites;
		}
		
		/**
		 * @return User
		**/
		public function fillInvites($collection, $lazy = false)
		{
			$this->invites = new UserInvitesDAO($this, $lazy);
			
			if (!$this->id) {
				throw new WrongStateException(
					'i do not know which object i belong to'
				);
			}
			
			$this->invites->mergeList($collection);
			
			return $this;
		}
		
		/**
		 * @return UserWatchedThreadsDAO
		**/
		public function getWatchedThreads($lazy = false)
		{
			if (!$this->watchedThreads || ($this->watchedThreads->isLazy() != $lazy)) {
				$this->watchedThreads = new UserWatchedThreadsDAO($this, $lazy);
			}
			
			return $this->watchedThreads;
		}
		
		/**
		 * @return User
		**/
		public function fillWatchedThreads($collection, $lazy = false)
		{
			$this->watchedThreads = new UserWatchedThreadsDAO($this, $lazy);
			
			if (!$this->id) {
				throw new WrongStateException(
					'i do not know which object i belong to'
				);
			}
			
			$this->watchedThreads->mergeList($collection);
			
			return $this;
		}
	}
?>