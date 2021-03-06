<?php

/**
 * PluginTwitterTweetLastFetchTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginTwitterTweetLastFetchTable extends Doctrine_Table
{
  /**
   * Returns an instance of this class.
   *
   * @return object PluginTwitterTweetLastFetchTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('PluginTwitterTweetLastFetch');
  }
  
  /**
   * Get the base Doctrine query for retrieving or updating the "last fetch time" for a given TwitterStatusQuery
   *
   * @param TwitterStatusQuery $query
   * @return Doctrine_Query
   */
  public function getBaseQueryForTwitterQuery(TwitterStatusQuery $query)
  {
    $q = $this->createQuery('f');

    if ($query->getParameter('q')) {
      $q->addWhere('f.keyword = ?', $query->getParameter('q'));
      return $q;
    }
    
    /* @var $query TwitterStatusUserTimelineQuery */
    if ($screen_name = $query->getParameter(TwitterStatusUserTimelineQuery::PARAM_SCREEN_NAME))
    {
      $q->addWhere('f.screen_name = ?', $screen_name);
    }
    
    if ($user_id = $query->getParameter(TwitterStatusUserTimelineQuery::PARAM_USER_ID))
    {
      $q->addWhere('f.user_id = ?', $user_id);
    }
    return $q;
  }
  
  /**
   * Get the "last fetch time" for a given TwitterStatusQuery
   *
   * @param TwitterStatusQuery $query
   * @return integer
   */
  public function getLastFetchTimeForQuery(TwitterStatusQuery $query)
  {
    $q = $this->getBaseQueryForTwitterQuery($query);
    $q->select('UNIX_TIMESTAMP(f.fetched_at) AS fetched_at');
    $last_fetch_time = $q->fetchOne(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
    if ($last_fetch_time)
    {
      return $last_fetch_time;
    }
    
    return false;
  }
  
  /**
   * Update the "last fetch time" for a given TwitterStatusQuery, for caching purposes
   *
   * @param TwitterStatusQuery $query
   */
  public function updateLastFetchTimeForQuery(TwitterStatusQuery $query)
  {
    $q = $this->getBaseQueryForTwitterQuery($query);
    $object = $q->fetchOne();
    if (!$object)
    {
      $object = new TwitterTweetLastFetch();
      if ($query->getParameter('q')) {
        $object->keyword = $query->getParameter('q');
      } else {
        $object->screen_name = $query->getParameter(TwitterStatusQuery::PARAM_SCREEN_NAME);
        $object->user_id = $query->getParameter(TwitterStatusQuery::PARAM_USER_ID);
      }
    }
    $object->fetched_at = date('Y-m-d H:i:s', time());
    $object->save();
  }
}
