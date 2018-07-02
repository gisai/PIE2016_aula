#!/bin/bash

user="maxwell"
password="fourier"
database="AULA_GISAI"
query="SELECT DISTINCT hashtag from Asignaturas"

variable=$(echo $query | mysql $database -u $user -p$password)

tweetQuery="twurl '/1.1/search/tweets.json?q="
orSentence=" OR "

for hashtag in $variable; do
	if [ $hashtag = "hashtag" ]; then
		continue
	fi
	tweetQuery=$tweetQuery$hashtag$orSentence
done

tweetQuery=${tweetQuery::-4}

tweetQuery=$tweetQuery"&from=gisai_upm' | jq '.' > .tweets" 

eval $tweetQuery

i=0

while [ $(eval "cat .tweets | jq '.statuses[$i].id_str'") != "null" ]; do
	tText=$(eval "cat .tweets | jq '.statuses[$i].text'")
	tFecha=$(eval "cat .tweets | jq '.statuses[$i].created_at'")
	tid=$(eval "cat .tweets | jq '.statuses[$i].id_str'")
	tMedia=""
	#tHashtag=$(eval "cat .tweets | jq '.statuses[$i].entities.hashtags[0].text'")

	query="(SELECT 1 FROM Tweets WHERE idTweet = '$tid');"

	resultado=$(echo $query | mysql $database -u $user -p$password)

	#vemos si el tweet no existe por su id
	if [[ $resultado == "" ]]; then
		#metemos el tweet

		#quitamos las comillas de la fecha
		temp="${tFecha%\"}"
		temp="${temp#\"}"
		tFecha=$temp
		
		#sacamos la fecha		
		fecha=$(eval "date -d '$tFecha' '+%Y-%m-%d %H:%M:%S'")

		#arreglamos tambien el texto
		temp="${tText%\"}"
		temp="${temp#\"}"
		tText=$temp

		#arreglamos la id
		temp="${tid%\"}"
		temp="${temp#\"}"
		tid=$temp

		#montamos el campo media
		k=0
		while [[ $(eval "cat .tweets | jq '.statuses[$i].entities.media[$k].media_url'") != "null" ]]; do
			#statements
			mediaAux=$(eval "cat .tweets | jq '.statuses[$i].entities.media[$k].media_url'")
			temp="${mediaAux%\"}"
			temp="${temp#\"}"
			mediaAux=$temp

			tMedia=$tMedia$mediaAux" "
			let "k=k+1"
		done

		j=0

		#por cada hasthag hay que meter un tweet en la BBDD
		while [[ $(eval "cat .tweets | jq '.statuses[$i].entities.hashtags[$j].text'") != "null" ]]; do
			#statements
			tHashtag=$(eval "cat .tweets | jq '.statuses[$i].entities.hashtags[$j].text'")
			temp="${tHashtag%\"}"
			temp="#${temp#\"}"
			tHashtag=$temp
	
			#buscamos la asignatura a la que hace referencia
			query2="SELECT idAsignatura FROM Asignaturas WHERE hashtag='$tHashtag'"
			asigId=$(echo $query2 | mysql $database -u $user -p$password)
			asigId=$(echo $asigId | awk '{print $2}')
	
			#metemos la asignatura en la tabla
			query3="INSERT INTO Tweets(idTweet,textTweet,fechaTweet,asignatura,media) VALUES ('$tid','$tText','$fecha','$asigId','$tMedia');"
			echo $query3 | mysql $database -u $user -p$password
			let "j=j+1"
		done
	fi
	let "i=i+1"
done