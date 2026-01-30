Array.parseFloat    	= function() 
{
	let res = [];
	if(arguments.length === 1)
		if(isArray(arguments[0]))
			for(let val of arguments[0])
				if(isArray(val)) res.push(Array.parseFloat.apply(null,val));
				else res.push(parseFloat(val));
		else res.push(parseFloat(arguments[0]));
	else for(let val of arguments)
			if(isArray(val)) res.push(Array.parseFloat.apply(null,val));
			else res.push(parseFloat(val));
	return res;
}

Array.parseInt    		= function() 
{
	let res = [];
	if(arguments.length === 1)
		if(isArray(arguments[0]))
			for(let val of arguments[0])
				if(isArray(val)) res.push(Array.parseInt.apply(null,val));
				else res.push(parseInt(val));
		else res.push(parseInt(arguments[0]));
	else for(let val of arguments)
			if(isArray(val)) res.push(Array.parseInt.apply(null,val));
			else res.push(parseInt(val));
	return res;
}

Array.forceParseFloat    	= function() 
{
	let res = [];
	if(arguments.length === 1)
		if(isArray(arguments[0]))
			for(let val of arguments[0])
				if(isArray(val)) res.push(Array.forceParseFloat.apply(null,val));
				else res.push(forceParseFloat(val));
		else res.push(forceParseFloat(arguments[0]));
	else for(let val of arguments)
			if(isArray(val)) res.push(Array.forceParseFloat.apply(null,val));
			else res.push(forceParseFloat(val));
	return res;
}

Array.forceParseInt    		= function() 
{
	let res = [];
	if(arguments.length === 1)
		if(isArray(arguments[0]))
			for(let val of arguments[0])
				if(isArray(val)) res.push(Array.forceParseInt.apply(null,val));
				else res.push(forceParseInt(val));
		else res.push(forceParseInt(arguments[0]));
	else for(let val of arguments)
			if(isArray(val)) res.push(Array.forceParseInt.apply(null,val));
			else res.push(forceParseInt(val));
	return res;
}

Array.tryParseFloat    		= function() 
{
	let res = [];
	if(arguments.length === 1)
		if(isArray(arguments[0]))
			for(let val of arguments[0])
				if(isArray(val)) res.push(Array.tryParseFloat.apply(null,val));
				else res.push(tryParseFloat(val));
		else res.push(tryParseFloat(arguments[0]));
	else for(let val of arguments)
			if(isArray(val)) res.push(Array.tryParseFloat.apply(null,val));
			else res.push(tryParseFloat(val));
	return res;
}

Array.tryParseInt    		= function() 
{
	let res = [];
	if(arguments.length === 1)
		if(isArray(arguments[0]))
			for(let val of arguments[0])
				if(isArray(val)) res.push(Array.tryParseInt.apply(null,val));
				else res.push(tryParseInt(val));
		else res.push(tryParseInt(arguments[0]));
	else for(let val of arguments)
			if(isArray(val)) res.push(Array.tryParseInt.apply(null,val));
			else res.push(tryParseInt(val));
	return res;
}

Array.values         	= function()
{
    return Array.from(expand.apply(null,arguments));
}

Array.count          	= function() 
{
    return Array.values.apply(null,arguments).length;
}

Array.distinct       	= function()
{
    return Array.values.apply(null,arguments).filter((v, i, a) => a.indexOf(v) === i);
}

Array.sort         		= function()
{
    return Array.values.apply(null,arguments).sort();
}

Array.frequent       	= function() 
{
	let arr = [];
    for(let val of expand.apply(null,arguments)) 
    {
		const ind = arr.findIndex(v=>v[0] == val);
		if(ind<0)
			arr.push([val,1]);
		else arr[ind][1] += 1;
	}
	return arr;
}

Array.mode           = function() 
{
	const arr = Math.frequent.apply(null,arguments);
    const max = Math.maximum(each(arr, a=>a[1]));
	let mds = [];
	for(let i = 0; i < arr.length; i++) 
        if(arr[i][1] === max)
			mds.push(arr[i][0]);
	return mds;
}
