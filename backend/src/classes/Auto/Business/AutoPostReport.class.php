<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2017-04-09 21:59:50                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoPostReport extends IdentifiableObject
	{
		protected $post = null;
		protected $postId = null;
		protected $date = null;
		protected $reason = null;
		protected $reportedBy = null;
		protected $reportedById = null;
		protected $reportedByIpHash = null;
		
		/**
		 * @return Post
		**/
		public function getPost()
		{
			if (!$this->post && $this->postId) {
				$this->post = Post::dao()->getById($this->postId);
			}
			
			return $this->post;
		}
		
		public function getPostId()
		{
			return $this->post
				? $this->post->getId()
				: $this->postId;
		}
		
		/**
		 * @return PostReport
		**/
		public function setPost(Post $post)
		{
			$this->post = $post;
			$this->postId = $post ? $post->getId() : null;
			
			return $this;
		}
		
		/**
		 * @return PostReport
		**/
		public function setPostId($id)
		{
			$this->post = null;
			$this->postId = $id;
			
			return $this;
		}
		
		/**
		 * @return PostReport
		**/
		public function dropPost()
		{
			$this->post = null;
			$this->postId = null;
			
			return $this;
		}
		
		/**
		 * @return Timestamp
		**/
		public function getDate()
		{
			return $this->date;
		}
		
		/**
		 * @return PostReport
		**/
		public function setDate(Timestamp $date)
		{
			$this->date = $date;
			
			return $this;
		}
		
		/**
		 * @return PostReport
		**/
		public function dropDate()
		{
			$this->date = null;
			
			return $this;
		}
		
		public function getReason()
		{
			return $this->reason;
		}
		
		/**
		 * @return PostReport
		**/
		public function setReason($reason)
		{
			$this->reason = $reason;
			
			return $this;
		}
		
		/**
		 * @return User
		**/
		public function getReportedBy()
		{
			if (!$this->reportedBy && $this->reportedById) {
				$this->reportedBy = User::dao()->getById($this->reportedById);
			}
			
			return $this->reportedBy;
		}
		
		public function getReportedById()
		{
			return $this->reportedBy
				? $this->reportedBy->getId()
				: $this->reportedById;
		}
		
		/**
		 * @return PostReport
		**/
		public function setReportedBy(User $reportedBy = null)
		{
			$this->reportedBy = $reportedBy;
			$this->reportedById = $reportedBy ? $reportedBy->getId() : null;
			
			return $this;
		}
		
		/**
		 * @return PostReport
		**/
		public function setReportedById($id = null)
		{
			$this->reportedBy = null;
			$this->reportedById = $id;
			
			return $this;
		}
		
		/**
		 * @return PostReport
		**/
		public function dropReportedBy()
		{
			$this->reportedBy = null;
			$this->reportedById = null;
			
			return $this;
		}
		
		public function getReportedByIpHash()
		{
			return $this->reportedByIpHash;
		}
		
		/**
		 * @return PostReport
		**/
		public function setReportedByIpHash($reportedByIpHash)
		{
			$this->reportedByIpHash = $reportedByIpHash;
			
			return $this;
		}
	}
?>