################################################################################
# Automatically-generated file. Do not edit!
################################################################################

# Add inputs and outputs from these tool invocations to the build variables 
C_SRCS += \
../src/AESEncryptionExample.c \
../src/cdecode.c \
../src/cencode.c \
../src/crypto.c \
../src/sha256.c 

OBJS += \
./src/AESEncryptionExample.o \
./src/cdecode.o \
./src/cencode.o \
./src/crypto.o \
./src/sha256.o 

C_DEPS += \
./src/AESEncryptionExample.d \
./src/cdecode.d \
./src/cencode.d \
./src/crypto.d \
./src/sha256.d 


# Each subdirectory must supply rules for building sources it contributes
src/%.o: ../src/%.c
	@echo 'Building file: $<'
	@echo 'Invoking: GCC C Compiler'
	gcc -O0 -g3 -Wall -c -fmessage-length=0 -MMD -MP -MF"$(@:%.o=%.d)" -MT"$(@:%.o=%.d)" -o "$@" "$<"
	@echo 'Finished building: $<'
	@echo ' '


