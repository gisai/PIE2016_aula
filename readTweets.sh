#!/bin/bash

user="maxwell"
password="fourier"
database="AULA_GISAI"

i=0

while [ $(eval "cat .tweets | jq '.statuses[$i].text'") != "null" ]; do
	tText=$(eval "cat .tweets | jq '.statuses[$i].text'")
	tFecha=$(eval "cat .tweets | jq '.statuses[$i].created_at'")
	tid=$(eval "cat .tweets | jq '.statuses[$i].id'")
	tHashtag=$(eval "cat .tweets | jq '.statuses[$i].entities.hashtags[0].text'")

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

		#arreglamos tambien el hashtag
		temp="${tHashtag%\"}"
		temp="#${temp#\"}"
		tHashtag=$temp

		#buscamos la asignatura a la que hace referencia
		query2="SELECT idAsignatura FROM Asignaturas WHERE hashtag='$tHashtag'"
		asigId=$(echo $query2 | mysql $database -u $user -p$password)
		asigId=$(echo $asigId | awk '{print $2}')

		#metemos la asignatura en la tabla
		query3="INSERT INTO Tweets(idTweet,textTweet,fechaTweet,asignatura) VALUES ('$tid','$tText','$fecha','$asigId');"
		echo $query3 | mysql $database -u $user -p$password
	fi

	let "i=i+1"
done
