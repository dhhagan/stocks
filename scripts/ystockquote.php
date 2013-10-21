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
		//$stat = "l1c1va2xj1b4j4dyekjm3m4rr5p5p6s7";
		  $stat = "l1o0p0h0g0a0b0c1m7m5k4j5r1d0y0d2d1k3a2m3m4x0s0t7c4g4v1s6s1t8kjvj1b4e0j4e7e9e8r5r0s7p6r6r7";
		//$stat = "l1o0p0h0g0a0b0c1m7m5k4j5r1d0y0d2d1k3a2m3m4x0s0t7c4g4v1s6s1j2t8kja5b6vj1b4e0j4e7e9e8r5r0s7p6r6r7";
		$line = $this->request($stat);
		$data = array();
		
		// pricing
		$data["price"] = $line[0]; //l1
		$data["open"] = $line[1]; //o0
		$data["close"] = $line[2]; //p0
		$data["high"] = $line[3]; //h0
		$data["low"] = $line[4]; //g0
		$data["ask"] = $line[5];  //a0
		$data["bid"] = $line[6]; //b0
		
		// changes
		$data["change"] = $line[7]; //c1
		$data["change_from_50day"] = $line[8]; //m7
		$data["change_from_200day"] = $line[9]; //m5
		$data["change_from_year_high"] = $line[10];  //k4
		$data["change_from_year_low"] = $line[11];  //j5
		//$data["change_in_percent"] = $line[]; //c0->currently not working?
		
		// dividends
		$data["dividend_pay_date"] = $line[12]; //r1
		$data["annual_div_yield"] = $line[13]; //d0
		$data["annual_div_yield_percent"] = $line[14]; //y0
		
		// date
		$data["trade_date"] = $line[15]; //d2
		$data["last_trade_date"] = $line[16]; //d1
		$data["last_trade_size"] = $line[17]; //k3
		
		// averages
		$data["avg_daily_volume"] = $line[18]; //a2
		$data["50_day_moving"] = $line[19]; //m3
		$data["200day_moving_avg"] = $line[20]; //m4	
		
		// misc
		$data["exchange"] = $line[21]; //x0
		$data["symbol"] = $line[22]; //s0
		$data["ticker_trend"] = $line[23]; //t7
		$data["currency"] = $line[24]; //c4
		$data["holdings_gain"] = $line[25]; //g4
		$data["holdings_value"] = $line[26]; //v1
		$data["revenue"] = $line[27]; //s6
		$data["shares_owned"] = $line[28]; //s1
		$data["one_year_target"] = $line[29]; //t8
		
		// 52 week pricing
		$data["52_week_high"] = $line[30]; //k
		$data["52_week_low"] = $line[31]; //j
		
		// volume
		$data["volume"] = $line[32]; //v
		$data["market_cap"] = $line[33]; //j1
		
		
		// ratios
		$data["book_val_per_share"] = $line[34]; //b4	
		$data["diluted_eps"] = $line[35]; //e0
		$data["ebitda"] = $line[36]; //j4
		$data["eps_est_current_year"] = $line[37]; //e7
		$data["eps_est_quart"] = $line[38]; //e9
		$data["eps_est_next_year"] = $line[39]; //e8
		$data["PEG_ratio"] = $line[40]; //r5
		$data["PE_ratio"] = $line[41]; //r0
		$data["short_ratio"] = $line[42]; //s7
		$data["book_price"] = $line[43]; //p6
		$data["EPS_est_curr_year"] = $line[44]; //r6
		$data["EPS_est_next_year"] = $line[45]; //r7	
		
		
		/* currently broken: it seems that these values sometimes contain commmas as the separator which obviously screws with the whole 
			comma-separated value thing
			
		$data["outstanding_shares"] = $line[46]; //j2->it seems there is an error with how yahoo formats this
		$data["ask_size"] = $line[47]; //a5
		$data["bid_size"] = $line[48]; //b6
		*/
		
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
	/*	
	public function get_changeInPercent() {
		// tested with FB 10/6/2013 -> passed
		$stat = 'c0';
		$line = $this->request($stat);
		
		return $line[0];
		}	
		*/
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
		
	public function get_changeInPercent() {
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
		
	public function get_holdingsGainPercentRealtime() {
		$stat = 'g5';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_holdingsGainRealtime() {
		$stat = 'g6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsValue() {
		$stat = 'v1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_holdingsValueRealtime() {
		$stat = 'v7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeDate() {
		$stat = 'd1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradePriceOnly() {
		$stat = 'l1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeRealtimeWithTime() {
		$stat = 'k1';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_lastTradeSize() {
		$stat = 'k3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeTime() {
		$stat = 't1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lastTradeWithTime() {
		$stat = 'l0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_lowLimit() {
		$stat = 'l3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_marketCapitalization() {
		$stat = 'j1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_marketCapRealtime() {
		$stat = 'j3';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_moreInfo() {
		$stat = 'i0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_name() {
		$stat = 'n0';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_notes() {
		$stat = 'n4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_oneYearTargetPrice() {
		$stat = 't8';
		$line = $this->request($stat);
		
		return $line[0];
		}

	public function get_orderBookRealtime() {
		$stat = 'i5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_PEGRatio() {
		$stat = 'r5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_PERatio() {
		$stat = 'r0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_PERatioRealtime() {
		$stat = 'r2';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_percentChangeFrom50dayMovingAverage() {
		$stat = 'm8';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_percentChangeFrom200dayMovingAverage() {
		$stat = 'm6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_changeInPercentFromYearHigh() {
		$stat = 'k5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_percentChangeFromYearLow() {
		$stat = 'j6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_previousClose() {
		$stat = 'p0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_open() {
		$stat = 'o';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_priceBook() {
		$stat = 'p6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_priceEPSEstimateCurrentYear() {
		$stat = 'r6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_priceEPSEstimateNextYear() {
		$stat = 'r7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_pricePaid() {
		$stat = 'p1';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_priceSales() {
		$stat = 'p5';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_revenue() {
		$stat = 's6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_sharesOwned() {
		$stat = 's1';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_sharesOutstanding() {
		$stat = 'j2';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_shortRatio() {
		$stat = 's7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_stockExchange() {
		$stat = 'x0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_symbol() {
		$stat = 's0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_tickerTrend() {
		$stat = 't7';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_tradeDate() {
		$stat = 'd2';
		$line = $this->request($stat);
		
		return $line[0];
		}
	
	public function get_tradeLinks() {
		$stat = 't6';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_tradeLinksAdditional() {
		$stat = 'f0';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_200day_moving_average() {
		$stat = 'm4';
		$line = $this->request($stat);
		
		return $line[0];
		}
		
	public function get_volume() {
		$stat = 'v';
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
		
	
	public function get_historical_prices($start_date,$end_date,$period) {
		/*
			Month and day must be decremented, while year is left the same
			Trading periods: 
				daily: d
				weekly: w
				monthly: m
				
			This returns an array of arrays; each array contains the following:
				Date, Open, High, Low, Close, Volume, Adjusted Close
		*/
		list($month_from, $day_from, $year_from) = explode('/',$start_date);
		list($month_to, $day_to, $year_to) = explode('/',$end_date);
		
		$month_from -= 1;
		$day_from -= 1;
		$month_to -= 1;
		$day_to -= 1;
		
		$url = "http://ichart.yahoo.com/table.csv?s={$this->ticker}&a={$month_from}&b={$day_from}&c={$year_from}&d={$month_to}&e={$day_to}&f={$year_to}&g={$period}&ignore=.csv";
		
		if ($file = fopen($url,"r")){
			$row = 0;
			$hist_data = array();
			while ($data = fgetcsv($file,200,",")) {
				if ($row != 0) {
					$daily = array();
					$daily["Date"] = $data[0];
					$daily["Open"] = $data[1];
					$daily["High"] = $data[2];
					$daily["Low"] = $data[3];
					$daily["Close"] = $data[4];
					$daily["Volume"] = $data[5];
					$daily["Adj Close"] = $data[6];
					
					array_push($hist_data, $daily);
					}
					
				$row++;
				}
			fclose($file);
			}
		else {
			echo "Error opening the file.<br />";
			}

		return $hist_data;
		
		}
}

	/* Example of how to use: 
	$FB = new ystockquote('FB'); // generates new ystockquote for FB
	$all = $FB->get_all(); // gets all data for FB
	$data = $FB->get_historical_prices('01/01/20012','10/14/2013','d'); // grabs daily historical prices for FB for the timeframe Jan 1, 2012 to Oct 10, 2013
	foreach ($all as $key=> $value){
		echo "{$key}: {$value} <br/>";
		}
	foreach ($data as $value) {
		print_r($value);
		echo "<br />";
		
		}
	*/

?>