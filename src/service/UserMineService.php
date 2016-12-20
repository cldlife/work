<?php
/**
 * @desc UserMineService
 */
class UserMineService extends BaseService {

  private function getUserMineDAO() {
    return DAOFactory::getInstance()->createUserMineDAO();
  }
  
  /**
   * @desc 获取我发的帖子列表
   */
  public function getMineThreadsByUid($uid, $page = 1, $pageSize = 10) {
    $list = array();
    if ($uid) {
      if ($page && $pageSize) {
        $offset = ($page - 1) * $pageSize;
        $list = $this->getUserMineDAO()->findMineThreadsWithUid($uid, $offset, $pageSize);
      }
    }
    return $list;
  }
  
  /**
   * @desc 添加我发的帖子记录
   */
  public function addMineThread($uid, $tid) {
    if ($uid && $tid) {
      return $this->getUserMineDAO()->insertMineThread($uid, $tid);
    }
    
    return FALSE;
  }
  
  /**
   * @desc 删除我发的帖子记录
   */
  public function deleteMineThread($uid, $tid) {
    if ($uid && $tid) {
      return $this->getUserMineDAO()->deleteMineThread($uid, $tid);
    }
    
    return FALSE;
  }
  
  /**
   * @desc 获取我关注列表
   */
  public function getMineFriendsByUid($uid, $page = 1, $pageSize = 10) {
    $list = array();
    if ($uid) {
      if ($page && $pageSize) {
        $offset = ($page - 1) * $pageSize;
        $list = $this->getUserMineDAO()->findMineFriendsWithUid($uid, $offset, $pageSize);
      }
    }
    return $list;
  }
  
  /**
   * @desc 获取我好友uid列表 (按$friendUids)
   */
  public function getMineFriendsByFriendUids($uid, Array $friendUids)  {
    $list = array();
    if ($uid && $friendUids) {
      $friends = $this->getUserMineDAO()->findMineFriendsWithFriendUids($uid, $friendUids);
      foreach ($friends as $friend) {
        $list[] = $friend['friend_uid'];
      }
    }
    return $list;
  }
  
  /**
   * @desc 获取我关注 (单个)
   */
  public function getMineFriendByFriendUid($uid, $friendUid) {
    $friend = array();
    if ($uid && $friendUid) {
      $friend = $this->getUserMineDAO()->findMineFriendWithFriendUid($uid, $friendUid);
    }
  
    return $friend;
  }
  
  /**
   * @desc 添加关注
   */
  public function addMineFriend($uid, $friendUid) {
    if ($uid && $friendUid && $uid != $friendUid) {
      return $this->getUserMineDAO()->insertMineFriend($uid, $friendUid);
    }
  
    return FALSE;
  }
  
  /**
   * @desc 删除关注
   */
  public function deleteMineFriend($uid, $friendUid) {
    if ($uid && $friendUid) {
      return $this->getUserMineDAO()->deleteMineFriend($uid, $friendUid);
    }
  
    return FALSE;
  }
  
  /**
   * @desc 获取我的屏蔽用户uid列表 (按$friendUids)
   */
  public function getMineDisappearUsersByUids($uid, Array $disappearUids)  {
    $list = array();
    if ($uid && $disappearUids) {
      $users = $this->getUserMineDAO()->findMineDisappearUsersWithUids($uid, $disappearUids);
      foreach ($users as $user) {
        $list[] = $user['disappear_uid'];
      }
    }
    return $list;
  }
  
  /**
   * @desc 获取我的屏蔽用户uid(单个)
   */
  public function getMineDisappearUsersByUid($uid, $disappearUid) {
    $user = array();
    if ($uid && $disappearUid) {
      $user = $this->getUserMineDAO()->findMineDisappearUsersWithUid($uid, $disappearUid);
    }
    return $user;
  }
  
  /**
   * @desc 添加关注
   */
  public function addMineDisappearUser($uid, $disappearUid) {
    if ($uid && $disappearUid && $uid != $disappearUid) {
      return $this->getUserMineDAO()->insertMineDisappearUser($uid, $disappearUid);
    }
    return FALSE;
  }
}