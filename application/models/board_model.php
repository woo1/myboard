<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Board_model extends CI_Model {
	function __construct()
    {    	
        parent::__construct();
    }

    //게시판 사용자 상태 변경
    function updBoarUsersts($option)
    {
        $user_sts   = $this->db->escape($option['user_sts']);
        $user_srno  = $this->db->escape($option['user_srno']);
        $board_srno = $this->db->escape($option['board_srno']);
        $reg_user_srno  = $this->db->escape($option['reg_user_srno']);

        $query = ' UPDATE NS_USER_BOARD ';
        $query .= ' SET USER_STS = '.$user_sts.' '; /*1:정상, 2:정지*/
        $query .= ' WHERE USER_SRNO = '.$user_srno.' ';
        $query .= '   AND BOARD_SRNO = '.$board_srno.' ';
        $query .= '   AND BOARD_SRNO = (SELECT BOARD_SRNO FROM NS_BOARD ';
        $query .= '                      WHERE REG_USER_SRNO = '.$reg_user_srno.' ';
        $query .= '                         AND BOARD_SRNO = '.$board_srno.') ';

        $this->db->query($query);
    }

    //내정보 - 내 게시판 관리 - 정상 사용자 목록
    function getMyboardUser($option)
    {
        $board_srno = $this->db->escape($option['board_srno']);
        $reg_user_srno = $this->db->escape($option['reg_user_srno']);

        $query = 'select B.NK_NAME, B.USER_SRNO, A.USER_JOIN_DT, A.BOARD_SRNO, ';
        $query .= 'CASE WHEN B.USER_SRNO = C.REG_USER_SRNO THEN \'Y\' ELSE \'N\' END AS ADM_YN, ';
        $query .= '( ';
        $query .= ' SELECT COUNT(*) AS CNT';
        $query .= ' FROM NS_BOARD_ARCL AA';
        $query .= ' WHERE AA.BOARD_SRNO = A.BOARD_SRNO ';
        $query .= '   AND AA.REG_USER_SRNO = B.USER_SRNO ';
        $query .= '   AND AA.STS = \'1\' ';
        $query .= ' ) AS ARCL_CNT ';
        $query .= 'from NS_USER_BOARD A, NS_USER B, NS_BOARD C ';
        $query .= 'where A.user_sts = \'1\' '; #정상 사용자
        $query .= 'and A.board_srno = '.$board_srno.' ';
        $query .= 'and A.USER_SRNO = B.USER_SRNO ';
        $query .= 'and A.BOARD_SRNO = C.BOARD_SRNO ';
        $query .= 'and C.REG_USER_SRNO = '.$reg_user_srno.' ';

        $result = $this->db->query($query)->result();
        return $result;
    }

    //내정보 - 내 게시판 관리 - 가입 신청자 목록
    function getJoinReqUser($option)
    {
        $board_srno = $this->db->escape($option['board_srno']);
        $reg_user_srno = $this->db->escape($option['reg_user_srno']);

        $query = 'select B.NK_NAME, B.USER_SRNO, A.USER_REQ_RSN, A.USER_REQ_DT, A.BOARD_SRNO ';
        $query .= 'from NS_USER_BOARD A, NS_USER B, NS_BOARD C ';
        $query .= 'where A.user_sts = \'3\' '; #가입신청
        $query .= 'and A.board_srno = '.$board_srno.' ';
        $query .= 'and A.USER_SRNO = B.USER_SRNO ';
        $query .= 'and A.BOARD_SRNO = C.BOARD_SRNO ';
        $query .= 'and C.REG_USER_SRNO = '.$reg_user_srno.' ';

        $result = $this->db->query($query)->result();
        return $result;
    }

    //내정보 - 내 게시판 관리
    function getMyboardMng($option)
    {
        #rownum 초기화
        $query2 = 'set @rownum:=0';
        $this->db->query($query2);

        $reg_user_srno = $this->db->escape($option['reg_user_srno']);
        $query = 'SELECT AA.BOARD_SRNO, BB.BOARD_NM, BB.BOARD_DESC, AA.CNT_A, AA.CNT_B ';
        $query .= ' ,@rownum:=@rownum+1 as RNUM ';
        $query .= 'FROM ';
        $query .= '(SELECT A.BOARD_SRNO ';
        $query .= ',SUM(CASE WHEN B.USER_STS = \'1\' THEN 1 ELSE 0 END) AS CNT_A '; #정상 사용자 건수
        $query .= ',SUM(CASE WHEN B.USER_STS = \'3\' THEN 1 ELSE 0 END) AS CNT_B '; #가입신청 사용자 건수
        $query .= 'FROM NS_BOARD A, NS_USER_BOARD B ';
        $query .= 'WHERE A.BOARD_SRNO = B.BOARD_SRNO ';
        $query .= 'AND A.REG_USER_SRNO = '.$reg_user_srno.' ';
        $query .= 'AND A.BOARD_STS = \'1\''; #정상
        $query .= 'GROUP BY A.BOARD_SRNO ';
        $query .= ') AA, NS_BOARD BB ';
        $query .= 'WHERE AA.BOARD_SRNO = BB.BOARD_SRNO ';

        $result = $this->db->query($query)->result();

        return $result;
    }

    //게시판명 중복 건수 확인
    function getBoardDupCnt($option)
    {
        $result = $this->db->get_where('NS_BOARD', array('board_nm'=>$option['board_nm']))->row();
        return $result;
    }

    //게시판 등록
    function addBoard($option)
    {
        $srno = $this->getBoardSrno();
        $this->db->set('board_srno', $srno);
        $this->db->set('board_nm', $option['board_nm']);
        $this->db->set('board_sts', '1');
        $this->db->set('board_desc', $option['board_desc']);
        $this->db->set('reg_user_srno', $option['reg_user_srno']);
        $this->db->set('reg_dttm', "DATE_FORMAT(now(), '%Y%m%d%H%i%s')", false);
        $this->db->insert('NS_BOARD');

        $this->addUserBoard(array('user_srno'=>$option['reg_user_srno'],
                                  'board_srno'=>$srno, 'user_sts'=>'1',
                                  'user_req_rsn'=>''));
    }

    //게시판 연결정보 등록
    function addUserBoard($option)
    {
        $this->db->set('user_srno', $option['user_srno']);
        $this->db->set('board_srno', $option['board_srno']);
        $this->db->set('user_sts', $option['user_sts']);
        $this->db->set('user_req_rsn', $option['user_req_rsn']);
        $this->db->set('user_req_dt', "DATE_FORMAT(now(), '%Y%m%d%H%i%s')", false);
        $this->db->set('user_join_dt', "DATE_FORMAT(now(), '%Y%m%d%H%i%s')", false);
        $this->db->insert('NS_USER_BOARD');
    }

    //게시판 식별번호 조회
    function getBoardSrno()
    {
        $rtn_srno = 0;
        $this->db->select_max('board_srno');
        $query = $this->db->get('NS_BOARD')->row();
        if($query->board_srno != null){
            $rtn_srno = $query->board_srno;
        }
        $rtn_srno++;

        return $rtn_srno;
    }
}
?>