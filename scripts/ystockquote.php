<?php
//ystockquote.php

class ystockquote {
	public function __construct($ticker) {
		$this->ticker = $ticker ;
		}
	public function request($stat) {
		// request() uses the csv yahoo finance api to grab the specific stats for a specific ticker
		//$url = "../quotes.csv";  //Test for when not connected to interwebs
		$url = "http://finance.yahoo.com/d/quotes.csv?s={$this->ticker}&f={$stat}&e=.csv";

		if ($handle = fopen($url,"r") !== FALSE){	
			$handle = fopen($url,"r");
			return fgetcsv($handle);
			}
		}
		
	public function get_all() {
		$stat = "l1c1va2xj1b4j4dyekjm3m4rr5p5p6s7";
		$line = $this->request($stat);

		$data = array();
		$data['price'] = $line[0];
		$data['change'] = $line[1];
		$data['volume'] = $line[2];
		$data['avg_daily_volume'] = $line[3];
		$data['stock_exchange'] = $line[4];
		$data['market_cap'] = $line[5];
		$data['book_value'] = $line[6];
		$data['ebitda'] = $line[7];
		$data['dividend_per_share'] = $line[8];
		$data['dividend_yield'] = $line[9];
		$data['earnings_per_share'] = $line[10];
		$data['52_week_high'] = $line[11];
		$data['52_week_low'] = $line[12];
		$data['50day_moving_avg'] = $line[13];
		$data['200day_moving_avg'] = $line[14];
		$data['price_earnings_ratio'] = $line[15];
		$data['price_earnings_growth_ratio'] = $line[16];
		$data['price_sales_ratio'] = $line[17];
		$data['price_book_ratio'] = $line[18];
		$data['short_ratio'] = $line[19];
		
		return $data;
		}
		
	public function get_afterHoursChangeRealtime() {
		// tested with FB 10/6/2013 -> N/A
		$stat = 'c8';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_annualizedGain() {
		// tested with FB 10/6/2013 -> '-'
		$stat = 'g3';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_ask() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'a0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_askRealtime() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'b2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_askSize() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'a5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_avgDailyVol() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'a2';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_bid() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'b0';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_bidRealtime() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'b3';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_bidSize() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'b6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_bookValuePerShare() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'b4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_change() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'c1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeInPercent() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'c0';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_changeFrom50DayMovingAvg() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'm7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeFrom200DayMovingAvg() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'm5';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_changeFromYearHigh() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'k4';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_changeFromYearLow() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'j5';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_changeInPercen() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'p2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeInPercentRealtime() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'k2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeRealtime() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'c6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_commision() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'c3';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_currency() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'c4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dayHigh() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'h0';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_dayLow() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'g0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dayRange() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'm0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dayRangeRealtime() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'm2';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_dayValueChange() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'w1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dayValueChangeRealtime() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'w4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dividendPayDate() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'r1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_trailingAnnualDividendYield() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'd0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_trailingAnnualDividendYieldInPercent() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'y0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dilutedEPS() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'e0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_ebitda() {
		$stat = 'j4';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_EPSEstimateCurrentYear() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'e7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_EPSEstimatenextQuarter() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'e9';
		$line = $this->request($stat);
		
		return $line[0];
		}	
	
	public function get_EPSEstimateNextYear() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'e8';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_exDividendDate() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'q0';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		
	public function get_50day_moving_average() {
		$stat = 'm3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_sharesFloat() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'f6';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_highLimit() {
		$stat = 'l2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsGain() {
		$stat = 'g4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsGainPercent() {
		$stat = 'g1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	////////////////////////////////////
	public function get_price() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'l1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_chang() {
		$stat = 'c1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_volume() {
		$stat = 'v';
		$line = $this->request($stat);
		
		return $line[0];
		}			
		
	public function get_stock_exchange() {
		$stat = 'x';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_market_cap() {
		$stat = 'j1';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_book_value() {
		$stat = 'b4';
		$line = $this->request($stat);
		
		return $line[0];
		}
	

		
	public function get_dividend_per_share() {
		$stat = 'd';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_dividend_yield() {
		$stat = 'y';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_earnings_per_share() {
		$stat = 'e';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_52_week_high() {
		$stat = 'k';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_52_week_low() {
		$stat = 'j';
		$line = $this->request($stat);
		
		return $line[0];
		}
			
		
	public function get_200day_moving_average() {
		$stat = 'm4';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_price_earnings_ratio() {
		$stat = 'r';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_price_earnings_growth_ratio() {
		$stat = 'r5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_price_sales_ratio() {
		$stat = 'p5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_price_book_ratio() {
		$stat = 'p6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_short_ratio() {
		$stat = 'ls7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		/*
	public function get_historical_prices($start_date,$end_date) {
		$stat = 'l1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		*/
}

	
	$FB = new ystockquote('FB');
	$data = $FB->get_holdingsGainPercent();
	echo "$FB->ticker: $data";

	

?>