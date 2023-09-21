#include<DHT.h>

#define cooler_pin 10
#define pump_pin 9
#define blue_pin 11

#define water_pin A4
#define DHT_pin 2
#define sun1_pin A1
#define sun2_pin A2
#define soil_pin A3
#define ph_pin A0

DHT dht(DHT_pin, DHT22);
int i=1;
class Control {
  public:
    Control(int arg_pin, int arg_speed) {
      pin = arg_pin;
      speed = arg_speed;
      pinMode(pin, OUTPUT);
      digitalWrite(pin, LOW);
    }
    void working() {
      state ? analogWrite(pin, speed) : digitalWrite(pin, LOW);
    }
    void setState(bool arg_state) {
      state = arg_state;
    }
    void setSpeed(byte arg_speed) {
      speed = arg_speed;
    }
  private:
    bool state = false;
    byte pin = 0;
    byte speed;
};
Control cooler(cooler_pin, 100);
Control pump(pump_pin, 100);
Control blue(blue_pin, 100);
void setup() {
  Serial.begin(9600);
  dht.begin();
}

void loop() {
  cooler.working();
  pump.working();
  blue.working();
  if(Serial.available()>0){
    int command = Serial.read() - '0';
    switch(command)
      {
        case 1: sendSensorsData();  break;
        case 2: blue.setState(true);     break;
        case 3: blue.setState(false);    break;
        case 4: cooler.setState(true);  break;
        case 5: cooler.setState(false); break;
        case 6: pump.setState(true);    break;
        case 7: pump.setState(false);   break;
        case 8: blue.setSpeed(i*25); if(i<10){i++;} Serial.println(i);break;
        case 9: blue.setSpeed(i*25); if(i>0){i--;} Serial.println(i);break;
    }
  }
}
void sendSensorsData(){
  int dht_temp = dht.readTemperature();
  int dht_humi = dht.readHumidity();
  int sun1 = analogRead(sun1_pin);
  int sun2 = analogRead(sun2_pin);
  int soil = analogRead(soil_pin);
  int water = analogRead(water_pin);
  int ph    = analogRead(ph_pin);
  
  String stringOne;
  stringOne += " "+String(dht_temp);
  stringOne += " "+String(dht_humi);
  stringOne += " "+String(soil);
  stringOne += " "+String(sun1);
  stringOne += " "+String(sun2);
  stringOne += " "+String(water);
  stringOne += " "+String(ph);
  Serial.println(stringOne);
}
