<?php
enum ScreenSize: string
{
	case XXLarg = "xxl";
	case XXLargRange = "xxl-range";
	case XLarg = "xl";
	case XLargRange = "xl-range";
	case Larg = "lg";
	case LargRange = "lg-range";
	case Medium = "md";
	case MediumRange = "md-range";
	case Small = "sm";
	case SmallRange = "sm-range";
	case XSmall = "xs";
	case XSmallRange = "xs-range";
	case XXSmall = "xxs";
	case XXSmallRange = "xxs-range";
}

class SilentException extends Exception
{
}
if (!error_reporting())
	set_exception_handler(function ($exception) {
		if ($exception instanceof SilentException) {
			report($exception->getMessage(), "log", true);
			report($exception->getTraceAsString(), "error", true);
		} else {
			error_log($exception->getMessage()); // Log other exceptions
		}
	});