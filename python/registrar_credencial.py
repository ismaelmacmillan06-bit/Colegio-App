import time
import requests
from smartcard.System import readers
from smartcard.util import toHexString

URL_BASE = "http://127.0.0.1:8000"

def leer_uid():
    r = readers()
    if not r:
        print("✗ No se detectó el lector NFC")
        return None
    
    try:
        lector = r[0]
        connection = lector.createConnection()
        connection.connect()
        GET_UID = [0xFF, 0xCA, 0x00, 0x00, 0x00]
        response, sw1, sw2 = connection.transmit(GET_UID)
        connection.disconnect()
        if sw1 == 0x90:
            return toHexString(response).replace(" ", "").upper()
    except:
        pass
    return None

def buscar_persona(uid):
    try:
        r = requests.get(f"{URL_BASE}/api/buscar-uid/{uid}", timeout=5)
        return r.json()
    except:
        return None

def asignar_uid(tipo, id_persona, uid):
    try:
        r = requests.post(f"{URL_BASE}/api/asignar-uid", json={
            "tipo": tipo,
            "id": id_persona,
            "nfc_uid": uid
        }, timeout=5)
        return r.json()
    except:
        return None

def main():
    print("=" * 50)
    print("  Registrador de Credenciales NFC")
    print("  Colegio Digital")
    print("=" * 50)
    print()

    while True:
        print("\n¿Qué quieres hacer?")
        print("1. Asignar credencial a ALUMNO")
        print("2. Asignar credencial a DOCENTE")
        print("3. Leer UID de una tarjeta")
        print("4. Salir")
        
        opcion = input("\nOpción: ").strip()

        if opcion == "4":
            print("Saliendo...")
            break

        elif opcion == "3":
            print("\nAcerca la tarjeta al lector...")
            for _ in range(20):
                uid = leer_uid()
                if uid:
                    print(f"✓ UID detectado: {uid}")
                    break
                time.sleep(0.5)
            else:
                print("✗ No se detectó ninguna tarjeta")

        elif opcion in ["1", "2"]:
            tipo = "alumno" if opcion == "1" else "docente"
            id_persona = input(f"\nEscribe el ID del {tipo}: ").strip()
            
            if not id_persona.isdigit():
                print("✗ ID inválido")
                continue

            print(f"\nAcerca la credencial NFC al lector...")
            uid = None
            for _ in range(20):
                uid = leer_uid()
                if uid:
                    break
                time.sleep(0.5)

            if not uid:
                print("✗ No se detectó ninguna tarjeta")
                continue

            print(f"✓ UID leído: {uid}")
            confirmar = input(f"¿Asignar este UID al {tipo} ID {id_persona}? (s/n): ").strip().lower()
            
            if confirmar == "s":
                resultado = asignar_uid(tipo, id_persona, uid)
                if resultado and resultado.get("success"):
                    print(f"✓ Credencial asignada correctamente a {resultado.get('nombre')}")
                else:
                    print(f"✗ Error: {resultado.get('mensaje') if resultado else 'Sin respuesta del servidor'}")
            else:
                print("Cancelado")

if __name__ == "__main__":
    main()