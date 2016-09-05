/**
*   EVA - Electronic Volume Analyzer.
*
*           by MADAC Team for eMAG Hackaton 2016.
*/
#include <Arduino.h>
#include <ArduinoJson.h>
#include <ESP8266WiFi.h>
#include <WebSocketsServer.h>
#include <Hash.h>

WebSocketsServer webSocket = WebSocketsServer(81);

#define USE_SERIAL Serial1

#define ECHO_PIN1     5//D1 - L1
#define TRIGGER_PIN1  4//D2

#define ECHO_PIN2     0//D3 - W1
#define TRIGGER_PIN2  2//D4

#define ECHO_PIN3     14//D5 - L2
#define TRIGGER_PIN3  12//D6

#define ECHO_PIN4     13//D7 - W2
#define TRIGGER_PIN4  15//D8

#define ECHO_PIN5     16//D0 - H
#define TRIGGER_PIN5  15//D8

const char* ssid     = "ssid";
const char* password = "********";

long Distance(long time)
{
    // speed of sound at sea level = 340.29 m / s
    // ((time)*(Speed of sound))/ toward and backward of object) * 10
   
    long DistanceCalc;                      // Calculation variable
    DistanceCalc = time * 0.034 / 2;        // Actual calculation in cm
    //DistanceCalc = (time / 2.9) / 2;      // Actual calculation in mm
    //DistanceCalc = time / 74 / 2;         // Actual calculation in inches
    
    return DistanceCalc;                    // return calculated value
}

long getSonicSensorData(uint8_t trigger, uint8_t echo, String which) {
  long duration, distance;

  digitalWrite(trigger, LOW);
  delayMicroseconds(2);

  digitalWrite(trigger, HIGH);
  delayMicroseconds(10);

  digitalWrite(trigger, LOW);

  duration = pulseIn(echo, HIGH);
  distance = Distance(duration);

  Serial.print("Sensor ");
  Serial.print(which);
  Serial.print(" -> ");
  Serial.println(distance);

  return distance;
}

char* getJsonDataFromSensors() {
      StaticJsonBuffer<200> jsonBuffer;

      JsonObject& object = jsonBuffer.createObject();

      //object.set("sens_length_init", 148);
      object.set("sens_length_1", getSonicSensorData(TRIGGER_PIN1, ECHO_PIN1, "L1"));
      delay(250);
      object.set("sens_length_2", getSonicSensorData(TRIGGER_PIN2, ECHO_PIN2, "W1"));
      delay(2500);

      //object.set("sens_width_init", 98);
      object.set("sens_width_1", getSonicSensorData(TRIGGER_PIN3, ECHO_PIN3, "L2"));
      delay(250);
      object.set("sens_width_2", getSonicSensorData(TRIGGER_PIN4, ECHO_PIN4, "W2"));
      delay(250);

      //object.set("sens_height_init", 100);
      object.set("sens_height_1", getSonicSensorData(TRIGGER_PIN5, ECHO_PIN5, "H"));
      
      char json[256];
      object.printTo(json, sizeof(json));

      return json;
}

void webSocketEvent(uint8_t num, WStype_t type, uint8_t * payload, size_t lenght) {
    String pld = (char*)payload;

    switch(type) {
        case WStype_DISCONNECTED:
            USE_SERIAL.printf("[%u] Disconnected!\n", num);
            Serial.println("User disconnected from websocket.");
            
            break;
        case WStype_CONNECTED:
            {
                IPAddress ip = webSocket.remoteIP(num);
                USE_SERIAL.printf("[%u] Connected from %d.%d.%d.%d url: %s\n", num, ip[0], ip[1], ip[2], ip[3], payload);
                Serial.println("User connected to websocket.");
        
                // send message to client
                //webSocket.sendTXT(num, "Connected");
            }
            break;
        case WStype_TEXT:
            USE_SERIAL.printf("[%u] get Text: %s\n", num, payload);
            Serial.println(pld);
            
            if (pld == "GET_MEASUREMENTS") {
                // send message to client
                webSocket.sendTXT(num, getJsonDataFromSensors());
            }

            // send data to all connected clients
            // webSocket.broadcastTXT("message here");
            break;
        case WStype_BIN:
            USE_SERIAL.printf("[%u] get binary lenght: %u\n", num, lenght);
            hexdump(payload, lenght);

            // send message to client
            // webSocket.sendBIN(num, payload, lenght);
            break;
    }

}

void WiFiEvent(WiFiEvent_t event) {
    Serial.printf("[WiFi-event] event: %d\n", event);

    switch(event) {
        case WIFI_EVENT_STAMODE_GOT_IP:
            Serial.println("WiFi connected");
            Serial.println("IP address: ");
            Serial.println(WiFi.localIP());
            break;
        case WIFI_EVENT_STAMODE_DISCONNECTED:
            Serial.println("WiFi lost connection");
            break;
    }
}

void setup() {
    Serial.begin(115200);

    // delete old config
    WiFi.disconnect(true);

    delay(1000);

    WiFi.onEvent(WiFiEvent);

    WiFi.begin(ssid, password);

    Serial.println();
    Serial.println();
    Serial.println("Wait for WiFi... ");

    webSocket.begin();
    webSocket.onEvent(webSocketEvent);

    pinMode(TRIGGER_PIN1, OUTPUT);
    pinMode(ECHO_PIN1, INPUT);
    
    pinMode(TRIGGER_PIN2, OUTPUT);
    pinMode(ECHO_PIN2, INPUT);
    
    pinMode(TRIGGER_PIN3, OUTPUT);
    pinMode(ECHO_PIN3, INPUT);
    
    pinMode(TRIGGER_PIN4, OUTPUT);
    pinMode(ECHO_PIN4, INPUT);
    
    pinMode(TRIGGER_PIN5, OUTPUT);
    pinMode(ECHO_PIN5, INPUT);
}

void loop() {
    delay(1000);
    webSocket.loop();
}
